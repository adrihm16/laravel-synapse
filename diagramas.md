# Diagramas del Proyecto — Tienda Web Synapse

Todos los diagramas están escritos en **Mermaid**.
Para renderizarlos puedes usar:

- [mermaid.live](https://mermaid.live) (online, copiar y pegar)
- VS Code con la extensión **Markdown Preview Mermaid Support**
- GitHub (los renderiza automáticamente en cualquier `.md`)

---

## 1. Arquitectura General del Sistema

```mermaid
flowchart TD
    A["CLIENTE (Navegador)\nTailwind CSS + Alpine.js"]
    B["CAPA DE RUTAS\nroutes/web.php (Laravel)"]
    C["MIDDLEWARE\nauth | verified | admin (AdminMiddleware)"]
    D["CONTROLADORES\nHttp/Controllers/\nAdmin/ + Public/"]
    E["COMPONENTES LIVEWIRE\napp/Livewire/\nCheckoutWizard, StoreCart"]
    F["CAPA DE SERVICIOS\nProductService | CategoryService | UserService\nOrderService | ImageService | HeroBannerService"]
    G["MODELOS\nEloquent ORM: Producto, Variante, Categoria,\nPedido, Carrito, User, HeroBanner, ..."]
    H["BASE DE DATOS\nMySQL 8.0 (Docker)"]

    A -- "HTTP / Livewire WebSocket" --> B
    B --> C
    C --> D & E
    D & E --> F
    F --> G
    G --> H
```

---

## 2. Diagrama Entidad-Relación (ER)

```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar name
        varchar email
        varchar password
        varchar rol
        timestamp email_verified_at
    }
    CATEGORIAS {
        bigint id_categoria PK
        varchar nombre
        varchar imagen
        timestamp deleted_at
    }
    PRODUCTOS {
        bigint id_producto PK
        varchar nombre
        text descripcion
        bigint id_categoria FK
        varchar brand
        decimal precio_base
        boolean destacado
        timestamp deleted_at
    }
    GRUPOS_OPCION_PRODUCTO {
        bigint id_grupo PK
        bigint id_producto FK
        varchar nombre
        varchar tipo
        int orden
    }
    VALORES_OPCION_PRODUCTO {
        bigint id_valor PK
        bigint id_grupo FK
        varchar nombre
        varchar hex_code
        varchar imagen
        decimal precio_extra
        int orden
    }
    VARIANTES {
        bigint id_variante PK
        bigint id_producto FK
        decimal precio
        int stock
        varchar sku
        timestamp deleted_at
    }
    VARIANTE_VALORES {
        bigint id PK
        bigint id_variante FK
        bigint id_valor FK
    }
    IMAGEN_PRODUCTOS {
        bigint id_imagen PK
        bigint id_producto FK
        bigint id_valor FK
        varchar ruta
        int orden
    }
    CARRITO {
        bigint id_carrito PK
        bigint id_usuario FK
        bigint id_variante FK
        int cantidad
    }
    PEDIDOS {
        bigint id_pedido PK
        bigint id_usuario FK
        datetime fecha
        decimal total
        enum estado
        varchar nombre_envio
        varchar direccion
        varchar ciudad
        varchar codigo_postal
        varchar provincia
        varchar telefono
    }
    DETALLE_PEDIDO {
        bigint id_detalle PK
        bigint id_pedido FK
        bigint id_variante FK
        int cantidad
        decimal precio_unitario
    }
    HERO_BANNERS {
        bigint id_banner PK
        varchar imagen_desktop
        varchar imagen_mobile
        varchar enlace
        varchar titulo
        boolean activo
        int orden
    }

    USERS           ||--o{ PEDIDOS              : "realiza"
    USERS           ||--o{ CARRITO              : "tiene"
    CATEGORIAS      ||--o{ PRODUCTOS            : "agrupa"
    PRODUCTOS       ||--o{ GRUPOS_OPCION_PRODUCTO : "define"
    GRUPOS_OPCION_PRODUCTO ||--|{ VALORES_OPCION_PRODUCTO : "contiene"
    PRODUCTOS       ||--o{ VARIANTES            : "genera"
    VARIANTES       ||--|{ VARIANTE_VALORES     : "se_combina"
    VALORES_OPCION_PRODUCTO ||--|{ VARIANTE_VALORES : "participa_en"
    PRODUCTOS       ||--o{ IMAGEN_PRODUCTOS     : "galeria_global"
    VALORES_OPCION_PRODUCTO |o--o{ IMAGEN_PRODUCTOS : "galeria_color"
    PEDIDOS         ||--|{ DETALLE_PEDIDO       : "contiene"
    VARIANTES       ||--o{ DETALLE_PEDIDO       : "aparece_en"
    VARIANTES       ||--o{ CARRITO              : "en"
```

---

## 3. Diagrama de Clases — Modelos Eloquent principales

```mermaid
classDiagram
    class Producto {
        +bigint id_producto
        +string nombre
        +text descripcion
        +decimal precio_base
        +boolean destacado
        +getPrecioAttribute() decimal
        +getImagenPrincipalAttribute() string
        +scopeFilter(array filters) Builder
        +variantes() HasMany
        +gruposOpciones() HasMany
        +imagenes() HasMany
        +categoria() BelongsTo
    }

    class Variante {
        +bigint id_variante
        +decimal precio
        +int stock
        +string sku
        +getOpcionesTextAttribute() string
        +producto() BelongsTo
        +valores() BelongsToMany
    }

    class GrupoOpcionProducto {
        +bigint id_grupo
        +string nombre
        +string tipo
        +int orden
        +producto() BelongsTo
        +valores() HasMany
    }

    class ValorOpcionProducto {
        +bigint id_valor
        +string nombre
        +string hex_code
        +decimal precio_extra
        +getImagenUrlAttribute() string
        +grupo() BelongsTo
    }

    class ImagenProducto {
        +bigint id_imagen
        +bigint id_valor
        +string ruta
        +int orden
        +getUrlAttribute() string
        +producto() BelongsTo
        +valor() BelongsTo
    }

    class User {
        +bigint id
        +string name
        +string email
        +string rol
        +isAdmin() bool
        +pedidos() HasMany
        +carrito() HasMany
    }

    class Pedido {
        +bigint id_pedido
        +decimal total
        +string estado
        +getReferenciaAttribute() string
        +scopeFilter(array filters) Builder
        +usuario() BelongsTo
        +detalles() HasMany
    }

    class Carrito {
        +bigint id_carrito
        +int cantidad
        +usuario() BelongsTo
        +variante() BelongsTo
    }

    class HeroBanner {
        +bigint id_banner
        +boolean activo
        +int orden
        +getImagenDesktopUrlAttribute() string
        +getImagenMobileUrlAttribute() string
    }

    class OrderStatus {
        <<enumeration>>
        Pendiente
        Pagado
        Enviado
        Entregado
        +values() array
        +manuallyAssignable() array
    }

    Producto       "1" --> "N" Variante              : tiene
    Producto       "1" --> "N" GrupoOpcionProducto   : define
    Producto       "1" --> "N" ImagenProducto        : galeria
    GrupoOpcionProducto "1" --> "N" ValorOpcionProducto : contiene
    ValorOpcionProducto "1" --> "N" ImagenProducto   : galeria_color
    Variante       "N" --> "M" ValorOpcionProducto   : combina
    User           "1" --> "N" Pedido                : realiza
    User           "1" --> "N" Carrito               : tiene
    Variante       "1" --> "N" Carrito               : en
    Pedido         ..>          OrderStatus          : usa
```

---

## 4. Diagrama de Estados del Pedido

```mermaid
stateDiagram-v2
    direction LR

    [*]        --> pendiente : Pedido creado\n(pre-pago)
    pendiente  --> pagado    : Checkout confirmado\n▸ automático (CheckoutWizard)
    pagado     --> enviado   : Admin actualiza estado
    enviado    --> entregado : Admin actualiza estado
    pendiente  --> enviado   : Admin corrige estado
    entregado  --> [*]

    note right of pagado
        Estado asignado automáticamente
        por el CheckoutWizard.
        No aparece en manuallyAssignable().
    end note
```

---

## 5. Diagrama de Secuencia — Proceso de Compra (Checkout)

```mermaid
sequenceDiagram
    actor Cliente
    participant Browser
    participant CW as CheckoutWizard<br/>(Livewire)
    participant DB as MySQL

    Cliente  ->> Browser : Pulsa "Ir al pago"
    Browser  ->> CW      : mount()
    CW       ->> DB      : SELECT carrito WHERE id_usuario = ?
    DB      -->> CW      : items del carrito
    CW      -->> Browser : Renderiza Paso 1 — Datos de envío

    Cliente  ->> Browser : Rellena nombre, dirección, etc.
    Browser  ->> CW      : updated(field) — validación en tiempo real
    CW      -->> Browser : Errores inline (si los hay)

    Cliente  ->> Browser : Pulsa "Continuar"
    Browser  ->> CW      : goToSummary()
    CW       ->> CW      : validate(shippingRules)
    CW      -->> Browser : Renderiza Paso 2 — Resumen del pedido

    Cliente  ->> Browser : Pulsa "Confirmar pedido"
    Browser  ->> CW      : confirm()

    CW       ->> DB      : BEGIN TRANSACTION

    loop por cada variante del carrito
        CW   ->> DB      : SELECT stock FROM variantes WHERE id_variante = ?
        DB  -->> CW      : stock actual
        alt stock insuficiente
            CW -->> Browser : Error "Sin stock suficiente para [producto]"
            CW ->> DB      : ROLLBACK
        end
    end

    CW       ->> DB      : INSERT INTO pedidos (estado='pagado', ...)
    DB      -->> CW      : id_pedido
    CW       ->> DB      : INSERT INTO detalle_pedido (N filas)
    CW       ->> DB      : UPDATE variantes SET stock = stock - cantidad
    CW       ->> DB      : DELETE FROM carrito WHERE id_usuario = ?
    CW       ->> DB      : COMMIT

    DB      -->> CW      : OK
    CW      -->> Browser : Renderiza Paso 3 — Confirmación SYN-XXXXXX
    Browser -->> Cliente : "¡Pedido realizado!"
```

---

## 6. Diagrama de Flujo — Navegación de la Aplicación

```mermaid
flowchart TD
    HOME["🏠 Inicio\n/"]
    CATALOG["📦 Catálogo\n/catalogo"]
    DETAIL["🔍 Detalle de producto\n/producto/{id}"]
    AUTH{{"¿Autenticado?"}}
    LOGIN["🔑 Login\n/login"]
    REGISTER["📝 Registro\n/register"]
    CART["🛒 Carrito\n/carrito\n(Livewire: StoreCart)"]
    CHECKOUT["💳 Checkout\n/checkout\n(Livewire: CheckoutWizard)"]
    CONFIRM["✅ Pedido confirmado\nReferencia SYN-XXXXXX"]
    PROFILE["👤 Perfil\n/profile"]

    subgraph ADMIN["Panel de Administración (rol=admin)"]
        DASHBOARD["📊 Dashboard\n/dashboard"]
        ADM_PROD["📦 Productos\n/dashboard/products"]
        ADM_CAT["🗂️ Categorías\n/dashboard/categories"]
        ADM_ORD["📋 Pedidos\n/dashboard/orders"]
        ADM_USR["👥 Usuarios\n/dashboard/users"]
        ADM_STATS["📈 Estadísticas\n/dashboard/stats"]
    end

    HOME      --> CATALOG
    HOME      --> DETAIL
    CATALOG   --> DETAIL
    DETAIL    --> AUTH
    AUTH      -- No --> LOGIN
    LOGIN     --> REGISTER
    AUTH      -- Sí --> CART
    CART      --> CHECKOUT
    CHECKOUT  --> CONFIRM
    LOGIN     --> PROFILE
    LOGIN     --> DASHBOARD
    DASHBOARD --> ADM_PROD & ADM_CAT & ADM_ORD & ADM_USR & ADM_STATS
```

---

## 7. Diagrama del Sistema de Variantes (Modelo Flexible)

Ejemplo con un iPhone 15 Pro para ilustrar cómo funciona el modelo de 4 tablas.

```mermaid
flowchart TD
    subgraph PROD["🏷️ Producto: iPhone 15 Pro\nprecio_base = 1.199 €"]
    end

    subgraph GRUPOS["Grupos de Opciones"]
        G1["🎨 Grupo: Color\ntipo = color"]
        G2["💾 Grupo: Almacenamiento\ntipo = texto"]
    end

    subgraph VALORES["Valores de Opciones"]
        V1["⬛ Negro Titanio\nhex=#1c1c1e · +0 €"]
        V2["⬜ Blanco Natural\nhex=#f5f5f0 · +0 €"]
        V3["256 GB · +0 €"]
        V4["512 GB · +200 €"]
        V5["1 TB · +400 €"]
    end

    subgraph VARIANTES["Variantes (SKU únicos)"]
        VAR1["SKU: IP15P-BLK-256\nprecio=1.199 € · stock=15"]
        VAR2["SKU: IP15P-BLK-512\nprecio=1.399 € · stock=8"]
        VAR3["SKU: IP15P-WHT-256\nprecio=1.199 € · stock=12"]
        VAR4["SKU: IP15P-WHT-512\nprecio=1.399 € · stock=5"]
    end

    PROD --> G1 & G2
    G1   --> V1 & V2
    G2   --> V3 & V4 & V5

    V1 & V3 --> VAR1
    V1 & V4 --> VAR2
    V2 & V3 --> VAR3
    V2 & V4 --> VAR4
```

---

## 8. Diagrama de la Capa de Servicios

```mermaid
flowchart LR
    subgraph CTRL["Controladores (delegadores)"]
        APC["AdminProductController"]
        ACC["AdminCategoryController"]
        AOC["AdminOrderController"]
        AHC["AdminHeroBannerController"]
        HC["HomeController\nProductController"]
    end

    subgraph SVC["Servicios (lógica de negocio)"]
        PS["ProductService\n─────────────\ncreateProduct()\nupdateProduct()\ndeleteProduct()\nforgetProductCaches()"]
        CS["CategoryService\n─────────────\ncreateCategory()\nupdateCategory()\ndeleteCategory()"]
        OS["OrderService\n─────────────\nchangeStatus()"]
        IS["ImageService\n─────────────\nstoreAsWebp()\ndelete()"]
        HS["HeroBannerService\n─────────────\nupdate()"]
    end

    subgraph PERSIST["Persistencia"]
        MODELS[("Eloquent Models\nProducto · Variante\nCategoria · Pedido\nHeroBanner · ...")]
        STORAGE[("Storage /public\nWebP images")]
        CACHE[("Cache\nproductos/categorías/banner")]
    end

    APC --> PS
    ACC --> CS
    AOC --> OS
    AHC --> HS
    HC  --> MODELS
    HC  --> CACHE

    PS  --> IS & MODELS & CACHE
    CS  --> IS & MODELS
    OS  --> MODELS
    HS  --> IS & MODELS & CACHE

    IS  --> STORAGE
    MODELS --> PERSIST
```

---

## 9. Diagrama de Infraestructura Docker

```mermaid
graph TB
    BROWSER(["🌐 Cliente\n(navegador)"])

    subgraph COMPOSE["docker-compose.yml"]
        direction TB

        subgraph APP["Contenedor: app\n(PHP 8.2 · Nginx)"]
            LARAVEL["Laravel 12\nPHP-FPM"]
            ASSETS["Assets compilados\nVite · Tailwind · Alpine"]
        end

        subgraph WORKER["Contenedor: worker\n(PHP 8.2)"]
            QUEUE["php artisan\nqueue:listen"]
        end

        subgraph DB_SVC["Contenedor: db\n(MySQL 8.0)"]
            MYSQL[("synapse_db")]
        end

        VOL_STORAGE[["Volume: app_storage\n/app/storage/app/public"]]
        VOL_DB[["Volume: mysql_data\n/var/lib/mysql"]]
    end

    BROWSER -- ":80 HTTP" --> APP
    APP  --> DB_SVC
    WORKER --> DB_SVC
    APP  --- VOL_STORAGE
    DB_SVC --- VOL_DB
```

---

## 10. Diagrama de Casos de Uso

```mermaid
flowchart LR
    VISITANTE(["👤 Visitante"])
    CLIENTE(["🛍️ Cliente\n(autenticado)"])
    ADMIN(["🔧 Administrador"])

    subgraph UC_PUB["Funcionalidades públicas"]
        UC1["Ver página de inicio"]
        UC2["Explorar catálogo\ncon filtros"]
        UC3["Ver detalle de producto"]
    end

    subgraph UC_CLIENT["Funcionalidades de cliente"]
        UC4["Añadir al carrito"]
        UC5["Gestionar carrito\n(cantidad / eliminar)"]
        UC6["Realizar pedido\n(checkout multipaso)"]
        UC7["Editar perfil"]
    end

    subgraph UC_ADMIN["Funcionalidades de administrador"]
        UC8["Ver dashboard con KPIs"]
        UC9["Gestionar productos\n(CRUD + variantes + imágenes)"]
        UC10["Gestionar categorías"]
        UC11["Gestionar pedidos\n(listar · ver · cambiar estado)"]
        UC12["Gestionar usuarios"]
        UC13["Ver estadísticas de ventas"]
        UC14["Actualizar hero banner"]
    end

    VISITANTE --> UC1 & UC2 & UC3
    CLIENTE   --> UC1 & UC2 & UC3
    CLIENTE   --> UC4 & UC5 & UC6 & UC7
    ADMIN     --> UC8 & UC9 & UC10 & UC11 & UC12 & UC13 & UC14
```
