# API

This documents the benchmark ingestion API implemented in `the-php-bench` and validated locally on March 27, 2026.

## Flow

1. Runner checks in and provides its public signing key.
2. Server returns a verification challenge.
3. Runner signs the exact challenge string with its private key.
4. Server verifies the detached signature and marks the runner key as verified.
5. Runner uploads a benchmark CSV and a detached signature over the benchmark digest.
6. Server stores the raw file and creates an inbox row with `is_processed = 0`.

## Endpoints

- `GET /api/health`
- `POST /api/runners/check-in`
- `POST /api/runners/verify-key`
- `POST /api/benchmarks/uploads`

## `POST /api/runners/check-in`

Registers or refreshes a benchmark runner and returns a challenge for key verification.

Request body:

```json
{
  "runner_uuid": "optional-stable-id",
  "name": "bench-node-1",
  "public_key": "base64url-ed25519-public-key",
  "metadata": {
    "host": "eimu",
    "environment": "prod"
  }
}
```

Response includes:

- runner record
- verification challenge
- signing instructions

## `POST /api/runners/verify-key`

Completes public-key verification with a detached Ed25519 signature over the exact challenge string returned by check-in.

Request body:

```json
{
  "runner_uuid": "runner-id",
  "signature": "base64url-detached-signature"
}
```

## `POST /api/benchmarks/uploads`

Multipart upload for a benchmark CSV.

Form fields:

- `runner_uuid`
- `signature`
- `benchmark` file upload

Signature input format:

```text
benchmark-sha256:<sha256hex>
```

The server verifies that message with the runner's registered public key, stores the file, and creates an inbox row with `is_processed = 0`.

## Local Validation

Validated locally against `http://127.0.0.1:18090`.

### Health Check

```bash
curl -sS http://127.0.0.1:18090/api/health
```

Expected response:

```json
{
  "ok": true,
  "service": "the-php-bench-results"
}
```

### Runner Check-In

```bash
curl -sS http://127.0.0.1:18090/api/runners/check-in \
  -H 'Content-Type: application/json' \
  --data '{
    "runner_uuid":"runner-1",
    "name":"bench-node-1",
    "public_key":"4JWEJSZ6Uegw-0OgiNPtXjqNfKrjS1J2olRRpDlZwlI",
    "metadata":{"host":"eimu","environment":"local-validation"}
  }'
```

The server returned a challenge. In the successful local validation run, it was:

```text
moHIGAvXRGo-3D6qO2VGVxaOG5fMNKXDe0fzo3thSkw
```

### Key Verification

The exact challenge string above was signed with the runner private key, producing this detached signature:

```text
fVdNu3WW9xp4ROYyHZg_OqH5pU4AX0M26t7J49QCUZ1fCkqJU-PbaOouDBCISTcRB7yAclYgJNTbhOeSMhVuCQ
```

Verification request:

```bash
curl -sS http://127.0.0.1:18090/api/runners/verify-key \
  -H 'Content-Type: application/json' \
  --data '{
    "runner_uuid":"runner-1",
    "signature":"fVdNu3WW9xp4ROYyHZg_OqH5pU4AX0M26t7J49QCUZ1fCkqJU-PbaOouDBCISTcRB7yAclYgJNTbhOeSMhVuCQ"
  }'
```

Expected result:

- `ok: true`
- runner `key_status: verified`

### Signed Benchmark Upload

Validated benchmark file:

```text
results/results-20260327224355-7a00b122-default-20260327-224355.csv
```

Validated SHA-256:

```text
db7d78bbe750ee2acd32c33d387052682930d68af3bc8a1140f07f2fc1e52001
```

Signature message:

```text
benchmark-sha256:db7d78bbe750ee2acd32c33d387052682930d68af3bc8a1140f07f2fc1e52001
```

Detached signature used successfully:

```text
WZVd2K72EuY9duvbPqU_Q6_N2deza9Xmm_0lnz28AqR-V1ouEoU7mjub0Lg9dFW_OZxv0dK_IsA0cJKfoeeIAQ
```

Upload request:

```bash
curl -sS http://127.0.0.1:18090/api/benchmarks/uploads \
  -F runner_uuid=runner-1 \
  -F signature=WZVd2K72EuY9duvbPqU_Q6_N2deza9Xmm_0lnz28AqR-V1ouEoU7mjub0Lg9dFW_OZxv0dK_IsA0cJKfoeeIAQ \
  -F benchmark=@results/results-20260327224355-7a00b122-default-20260327-224355.csv
```

Expected result:

- `ok: true`
- inbox row created
- `is_processed: 0`
- file stored under `var/uploads/<runner_uuid>/...`

## Notes

- Signing algorithm used in the implementation and validation run: `ed25519`
- Detached signatures and public keys are transmitted as base64url values
- The upload endpoint currently persists runner/inbox state in SQLite for local validation
- The benchmark normalization/import worker remains a separate step after inbox ingestion
