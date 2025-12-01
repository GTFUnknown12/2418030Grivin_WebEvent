<!-- Copied/created by AI assistant: actionable guidance for code agents contributing to this repo -->
# Copilot / AI Agent Instructions for this repository

Purpose: quickly orient a coding agent to the project's structure, runtime assumptions, data flows, and conventions so suggested changes are immediately safe and useful.

Key points (read first)
- Language & style: this is a small, procedural PHP + static frontend project (no framework). Pages live in the repo root as `.php` or `.html` files (e.g. `index.php`, `Register.php`, `admin.php`). Keep changes compatible with the existing procedural pattern.
- Two persistence modes:
  - File-based JSON used for event registrations: `data/registrations.json` (written with `file_put_contents('data/registrations.json', ...)`). Examples: `index.php` registers users by appending to this file; `admin.php` reads/modifies it.
  - MySQL via `koneksi.php`: shared DB connection using `mysqli_connect` (database `db_cwnx`). Example usage: `include 'koneksi.php';` in `Register.php`.
- JavaScript lives inline in pages (not a bundler). The frontend fetches `data/events.json` (see `index.php`), uses `localStorage`, and embeds interactive scripts directly in the HTML. Prefer simple, vanilla JS changes.

File/dir map of interest
- `koneksi.php` — single source of truth for DB connection credentials and mysqli usage.
- `data/` — JSON files (`events.json`, possible `registrations.json`) and is expected to be writable by the web server during local dev.
- `css/`, `images/`, `assets/js/` — static assets. Avoid global CSS renames; follow existing class names.
- `Register.php` — example of DB insertion via concatenated SQL and `password_hash()` for passwords. Uses `include 'koneksi.php'`.
- `admin.php` — example of reading/writing `data/registrations.json`, and of simple admin actions via GET params like `admin.php?action=approve&id=...`.

Guidelines for safe, helpful changes
- Preserve existing storage choices unless a PR explicitly moves all related flows. If you must migrate JSON -> DB, produce a migration plan and convert code in small, reversible steps.
- When editing PHP that writes JSON, keep the same encoding style: `json_encode($data, JSON_PRETTY_PRINT)` and guard with `is_dir('data')` / `mkdir(...)` as existing files expect.
- When touching DB code: use `koneksi.php` for connections. New queries should prefer prepared statements (mysqli prepare + bind) to avoid SQL injection — but if you modify existing concatenated queries, include a clear migration note in the PR.
- Keep UI endpoints stable. Many pages rely on `GET` links (`admin.php?action=approve...`) and on specific JSON keys (e.g., registration objects have keys: `id`, `name`, `email`, `event`, `ticket_type`, `status`). Preserve those keys and expected value shapes.

Developer / runtime notes (how to run locally)
- This repo expects to run on a local PHP server (Laragon used by the author). Two quick options on Windows (PowerShell):
  - Laragon: start Laragon and open project `d:\\laragon\\www\\cwnX` in the www directory.
  - PHP built-in server for quick testing:
    ```powershell
    cd D:\\laragon\\www\\cwnX
    php -S localhost:8000
    # then open http://localhost:8000 in the browser
    ```
- Ensure `data/` exists and is writable by the webserver (otherwise page writes will fail). Example: create it with `mkdir data` and set permissions.

Patterns & examples agents should follow
- JSON registration flow (example): read -> modify -> write
  - Read: `$registrations = json_decode(file_get_contents('data/registrations.json'), true);`
  - Modify: update the array objects (status / push new entry)
  - Write: `file_put_contents('data/registrations.json', json_encode($registrations, JSON_PRETTY_PRINT));`
- DB usage (example): include `koneksi.php` then use `$koneksi` returned by `mysqli_connect`.
- Passwords: new user registration already uses `password_hash()`; follow that for any new authentication code.

What NOT to do without approval
- Do not replace the storage method (JSON ↔ MySQL) across files in a single PR without migration steps and tests.
- Do not change global CSS classnames or remove hardcoded asset paths (e.g., `css/` filenames) — many pages reference them directly.

Testing & validation checks for PRs
- Smoke test flows locally:
  - Register a user via the front-end registration flow (or submit POST to `index.php`), verify the new entry appears in `data/registrations.json`.
  - Exercise admin approve/reject links on `admin.php` and verify `status` updates in the JSON file.
  - If changing DB code, verify `Register.php` still allows sign-ups and passwords are hashed.

Small TODOs agents may suggest in PRs
- Convert string-concatenated SQL in `Register.php` to prepared statements.
- Add a small helper function to centralize JSON read/write for `data/registrations.json` (but keep existing calls compatible).

If you need more context
- Open `koneksi.php`, `index.php`, `Register.php`, and `admin.php` — these four files illustrate the primary flows and constraints.
- Ask the repo owner about preferred credential handling (currently plain text in `koneksi.php`) before moving secrets.

If something is ambiguous, ask the maintainer before changing: migration of storage, credential moves, or site-wide CSS/JS refactors.

---
After you review this file, tell me which section you want expanded or a follow-up task (migration plan, helper function, or PR to fix SQL injection).
