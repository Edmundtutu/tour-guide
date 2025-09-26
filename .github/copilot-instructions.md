<!-- .github/copilot-instructions.md: Guidance for AI coding agents working on the tour-guide app -->
# Tour-Guide — AI contributor notes

This file gives focused, actionable information for AI coding agents to be immediately productive in this repository.

- Project type: small PHP MVC-style app (no framework). Files are under `app/` (controllers, services, models, core). Public entry is `public/index.php`.
- DB: MySQL schema in `database/schema.sql`. DB config in `config/config.php` (DB_NAME, DB_USER, DB_PASS, BASE_URL).

Key design notes
- Routing: Simple home-grown router at `app/core/Router.php`. Routes are registered in `public/index.php` using Router->addRoute(...). The router matches exact paths (no param patterns).
- Layering: Controllers (app/controllers) use Services (app/services) which use Models (app/models) that extend `app/core/BaseModel.php` (generic CRUD helpers). `app/core/Database.php` is a PDO singleton.
- Auth: Session-based utilities live in `app/core/Auth.php`. Services and controllers call `Auth::requireLogin()` / `Auth::requireRole()` to guard actions.

Practical conventions an agent should follow
- File-loading: The app relies on manual require_once calls in `public/index.php`. Avoid adding new auto-loaders unless you update `public/index.php` to include them.
- Database access: Use models that extend `BaseModel`. Prefer adding new methods to models (e.g., `findBy...`) over raw SQL sprinkled in controllers. When writing SQL, follow existing prepared-statement style and bind parameters.
- Errors: The app throws Exceptions for flow control (e.g., permission checks). Catch exceptions in controllers where present; use existing patterns (renderError or redirect) rather than adding global handlers.
- Views: Many controllers render HTML directly (temporary). If you add view files under `views/`, register them consistently and update controllers to include those views. Don't change UI patterns without updating corresponding controllers.

Routing and URLs
- BASE_URL is defined in `config/config.php` and used throughout views/controllers. Use `BASE_URL` for constructing links/redirects.
- Because Router matches exact paths, any new route must be added in `public/index.php` via `$router->addRoute(...)`.

Security checks and roles
- Roles: `tourist`, `host`, `admin`. Services and controllers expect role checks via `Auth::requireRole('role')` or `Auth::requireLogin()`.
- Passwords: Use `Auth::hashPassword()` and `Auth::verifyPassword()` when creating or validating credentials.

Testing, build, and run notes
- Run locally with XAMPP / Apache + PHP and a MySQL DB named in `config/config.php`. Schema is in `database/schema.sql`.
- To boot the app in dev: place the project in your webserver's docroot (example: XAMPP htdocs) and point browser to `BASE_URL` (default in config is `http://localhost/tour-guide/public`).
- There are no automated tests or build scripts. Keep changes minimal and run manual smoke tests by exercising routes in a browser.

Patterns & examples
- Add a route: see `public/index.php`. Example for a new GET route:

  $router->addRoute('GET', '/example', 'ExampleController', 'index');

- Service → Model pattern: e.g., `app/services/HotelService.php` calls `$this->hotelModel = new Hotel();` and uses `findById()` / `create()` from `BaseModel`.

- DB helper usage: `BaseModel::create($data)` returns last insert id. Use named placeholders and `bindValue` like existing models.

When changing data models
- If you alter table columns, update `database/schema.sql` (for future DBs) and search for usages of columns across `app/` (controllers/services/models) to update references.

Quick grep targets
- Controllers: `app/controllers/*.php`
- Services: `app/services/*.php`
- Models: `app/models/*.php`
- Core: `app/core/*.php`

Small, safe improvements an agent may do autonomously
- Add a short README entry describing local run steps (if missing) or improve `README.md` with real content.
- Add input validation helpers in `app/core/` and use them in services (low-risk refactor).

What to avoid
- Don't introduce Composer autoloading or change folder layout without updating `public/index.php` bootstrapping.
- Avoid global session or DB changes that break the singleton `Database` or `Auth` expectations.

If you need clarification
- Ask the human maintainer which environment values they use (DB_NAME, BASE_URL) before changing `config/config.php`.
