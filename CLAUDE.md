# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

Stack: Laravel 12 (PHP 8.2), Livewire 4, Vite + Tailwind v4 + Alpine.js, PHPUnit 11.

- `composer dev` — runs the full dev environment (`php artisan serve`, `queue:listen`, `pail`, `npm run dev`) under `concurrently`. Use this rather than starting `artisan serve` and `vite` separately.
- `composer setup` — first-time install: composer install, copy `.env`, `key:generate`, `migrate --force`, `npm install`, `npm run build`.
- `composer test` — runs `config:clear` then `artisan test`. Tests use SQLite `:memory:` (see `phpunit.xml`), so no test DB setup is needed.
- `php artisan test --filter=SomeTest` — run a single test class/method.
- `php artisan migrate:fresh --seed` — wipe and reseed the DB (used heavily; see "Variant refactor" caveat below).
- `npm run build` — production asset build. `npm run dev` alone if you only need Vite HMR.
- `vendor/bin/pint` — Laravel Pint formatter (configured via dev dependency).

## Architecture

### Domain (Spanish naming)

Models, tables, columns, and route segments are in Spanish (`producto`, `carrito`, `pedido`, `categoria`, `variante`, `grupos_opcion_producto`, `valores_opcion_producto`). The English-language Laravel auth/profile scaffolding (from Breeze) coexists with this Spanish domain — don't translate one into the other when extending.

Custom primary keys are the norm: `id_producto`, `id_carrito`, `id_pedido`, `id_categoria`, `id_variante`, `id_grupo`, `id_valor`. Every Eloquent relationship must specify both FK and owner key explicitly (`hasMany(X::class, 'id_producto', 'id_producto')`) — Laravel's conventions don't apply. `Producto` overrides `getRouteKeyName()` to `id_producto` for route-model binding.

### Product / Variant model (post-2026-05-13 refactor)

The variant system was rewritten on 2026-05-13 (`2026_05_13_095733_refactor_variant_architecture`). The old `variantes_producto` table had hardcoded `color` and `almacenamiento` columns; that has been replaced by a flexible 4-table model:

- `productos` — base product, holds `precio_base`.
- `grupos_opcion_producto` — option groups per product (e.g., "Color", "Almacenamiento"), with `tipo` of `color` or `texto` and an `orden`.
- `valores_opcion_producto` — option values within a group (e.g., "Sand Storm", "512GB"), with optional `hex_code`, `imagen`, and `precio_extra`.
- `variantes` — concrete purchasable SKU with `precio`, `stock`, `sku`.
- `variante_valores` — pivot connecting a `variante` to its selected option values (one per group).

`Producto::getPrecioAttribute()` returns min variant price or `precio_base` as fallback. `Producto::getImagenPrincipalAttribute()` cascades: gallery image → first color-option value image → `asset('assets/<name>.png')`.

**The refactor migration truncates `carrito` and `detalle_pedido`** before recreating the FKs, so re-running migrations destroys cart/order data. Use `migrate:fresh` rather than rolling back through this migration in dev. `Producto`, `Variante`, and `Categoria` use `SoftDeletes`; `Carrito`, `Pedido`, `DetallePedido` do not.

### Service layer for transactional writes

`ProductService` (and `CategoryService`, `UserService`) wrap multi-step writes in `DB::transaction`. `ProductService::createProduct` does product → groups → values → variants → gallery in one transaction, using a `valorIdMap` keyed by `"{groupIndex}_{valueIndex}"` to map the form's nested array indices to real `id_valor`s when syncing variants to their option values. When adding product-related write logic, extend the service rather than the controller — `AdminProductController` is intentionally thin.

### Admin area

Admin routes are under `/dashboard` with `name('admin.')` prefix, gated by `auth` + the `admin` middleware alias (`bootstrap/app.php`). `AdminMiddleware` checks `auth()->user()->rol === 'admin'` — the role is the `rol` column on `users` (added in `2026_02_24_185758_modify_users_table`). `User::isAdmin()` mirrors this. Resource routes for users/categories/products live in `routes/web.php`; controllers in `app/Http/Controllers/Admin/`; form-request validation in `app/Http/Requests/Admin/`.

### Cart & checkout (Livewire + controller hybrid)

There are two cart paths: classic controller (`CartController` with redirects) and Livewire (`StoreCart` for inline qty +/− without page reload). They both read/write the same `carrito` table — keep state shape consistent if you touch either. Checkout is a Livewire wizard (`CheckoutWizard`) that pre-fills `nombreEnvio` from `auth()->user()->name` and writes shipping fields directly onto `pedidos` (no separate address table). Validation messages in `CheckoutWizard` are Spanish — match that when extending.

### Frontend

Tailwind v4 via `@tailwindcss/vite` (no `tailwind.config.js` at root — config is inline in CSS). Alpine.js is used for interactive widgets in Blade. Livewire components have paired views under `resources/views/livewire/`.

### Legacy code

`ProyectoIntermodular/` is the original raw-PHP version of the same shop (`bd_synapse.sql`, hand-rolled controllers). It is NOT part of the Laravel app — don't import from it, don't run its scripts. Use it only as a reference for the original schema/UX intent when porting features.

## Conventions in this codebase

- Form requests live in `app/Http/Requests/{Admin,Auth}/` and are the canonical place for validation — controllers should not re-validate.
- Filter scopes on models (`Producto::scopeFilter`, `User::scopeFilter`) take an array; admin index controllers pass `$request->only(...)` into them.
- Eager-load option/variant chains together: `with(['variantes.valores', 'gruposOpciones.valores'])` — partial loads will N+1 the catalog page.
- Validation/UI strings are in Spanish; system/log strings and code identifiers in English. Match the existing language of the area you're editing.
