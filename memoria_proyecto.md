# DPTO INFORMÁTICA – IES TRAFALGAR
## MÓDULO – PROYECTO INTEGRADO
### C.F.G.S. DESARROLLO DE APLICACIONES WEB (DAW)

---

# TIENDA WEB SYNAPSE

**Autor:** Adrián Herrera Morales

**Fecha:** Mayo 2026

**Tutora:** Marta López Roldán

---

---

## HOJA RESUMEN

| **Título del proyecto:** | Tienda Web Synapse |
|---|---|
| **Autor:** | Adrián Herrera Morales | **Fecha:** | Mayo 2026 |
| **Tutora:** | Marta López Roldán | | |
| **Ciclo Formativo:** | CFGS Desarrollo de Aplicaciones Web (DAW) | **Curso:** | 2025-2026 |
| **Palabras clave:** | Laravel, PHP, Livewire, e-commerce, tienda online, variantes de producto, panel de administración, Docker, Tailwind CSS |
| **Resumen del proyecto:** | Synapse es una aplicación web de comercio electrónico desarrollada con Laravel 12 y PHP 8.2 que permite a los clientes explorar un catálogo de productos con variantes flexibles (color, almacenamiento, etc.), gestionar un carrito de compras y completar pedidos mediante un asistente de pago multipaso. Incluye un completo panel de administración para gestionar productos, categorías, pedidos, usuarios y estadísticas de ventas. La aplicación se despliega mediante contenedores Docker. |

---

---

## ÍNDICE

1. Introducción
   - 1.1. Descripción General del Proyecto
   - 1.2. Objetivos Generales
   - 1.3. Motivación Personal
   - 1.4. Alcance del Proyecto

2. Estudio de la Viabilidad
   - 2.1. Análisis de la Situación Actual
   - 2.2. Diagnóstico de la Problemática
   - 2.3. Viabilidad Técnica
   - 2.4. Viabilidad Económica
   - 2.5. Viabilidad Temporal

3. Alternativas y Selección de la Solución
   - 3.1. Alternativas Consideradas
   - 3.2. Evaluación de Riesgos
   - 3.3. Selección de la Solución

4. Análisis y Diseño de la Solución Adoptada
   - 4.1. Requisitos Funcionales
   - 4.2. Requisitos No Funcionales
   - 4.3. Casos de Uso
   - 4.4. Arquitectura del Sistema
   - 4.5. Diseño de la Base de Datos
   - 4.6. Diseño de la Interfaz

5. Implementación
   - 5.1. Entorno de Desarrollo
   - 5.2. Estructura del Proyecto
   - 5.3. Capa de Datos: Modelos y Migraciones
   - 5.4. Capa de Servicios
   - 5.5. Capa de Presentación: Controladores y Rutas
   - 5.6. Componentes Livewire
   - 5.7. Sistema de Imágenes y Almacenamiento
   - 5.8. Sistema de Autenticación y Autorización
   - 5.9. Despliegue con Docker

6. Pruebas
   - 6.1. Estrategia de Pruebas
   - 6.2. Pruebas Unitarias
   - 6.3. Pruebas de Integración (Feature Tests)
   - 6.4. Pruebas Manuales

7. Costes y Presupuesto
   - 7.1. Costes de Desarrollo
   - 7.2. Costes de Infraestructura
   - 7.3. Costes de Mantenimiento
   - 7.4. Presupuesto Total

8. Conclusiones
   - 8.1. Grado de Consecución de Objetivos
   - 8.2. Dificultades Encontradas
   - 8.3. Posibles Ampliaciones
   - 8.4. Valoración Personal

9. Bibliografía

10. Glosario

11. Anexos
    - Anexo A: Diagrama Entidad-Relación
    - Anexo B: Diagrama de Rutas
    - Anexo C: Manual de Instalación
    - Anexo D: Manual de Usuario

---

---

# 1. INTRODUCCIÓN

## 1.1. DESCRIPCIÓN GENERAL DEL PROYECTO

   Synapse es una plataforma de comercio electrónico desarrollada como proyecto integrado del Ciclo Formativo de Grado Superior de Desarrollo de Aplicaciones Web (DAW). Se trata de una tienda online completa, orientada a la venta de productos tecnológicos, que implementa todas las funcionalidades propias de un sistema de e-commerce moderno: catálogo de productos con filtros avanzados, carrito de compras, proceso de pago con asistente multipaso, y un panel de administración completo.

   La aplicación ha sido construida siguiendo las prácticas actuales de desarrollo web profesional, empleando el framework Laravel 12 junto con Livewire 4 para la interactividad sin recargas de página. El diseño se apoya en Tailwind CSS v4 y Alpine.js. Todo el entorno de ejecución se encapsula mediante Docker, lo que garantiza la reproducibilidad de los despliegues.

   Una de las características más destacadas del sistema es su flexible modelo de variantes de producto. A diferencia de las implementaciones habituales con columnas fijas (como "color" o "talla"), Synapse implementa un sistema dinámico de grupos de opciones y valores, que permite definir cualquier combinación de atributos para cada producto, con imágenes y ajustes de precio por variante.

## 1.2. OBJETIVOS GENERALES

Los objetivos que se persiguen con la realización de este proyecto son los siguientes:

1. Desarrollar una aplicación web de comercio electrónico funcional y completa, lista para su uso en un entorno real.
2. Aplicar los conocimientos adquiridos a lo largo del ciclo formativo, integrando tecnologías frontend y backend modernas.
3. Implementar un sistema de gestión de productos flexible con soporte para variantes con múltiples atributos.
4. Desarrollar un panel de administración que permita gestionar todos los aspectos del negocio: productos, categorías, pedidos, usuarios y estadísticas de ventas.
5. Garantizar la calidad del software mediante pruebas automatizadas y una arquitectura limpia basada en la capa de servicios.
6. Desplegar la aplicación en un entorno contenerizado con Docker, reproducible en cualquier máquina.

## 1.3. MOTIVACIÓN PERSONAL

   La motivación principal para elegir este proyecto surge de la combinación entre el interés personal por el comercio electrónico y el deseo de consolidar los conocimientos de desarrollo web en un proyecto de magnitud real. Durante el ciclo, se han trabajado conceptos y tecnologías de forma fragmentada; este proyecto integrado representa la oportunidad de unirlos todos en un sistema coherente y completo.

   Asimismo, Laravel es uno de los frameworks PHP más demandados en el mercado laboral, y la adquisición de experiencia práctica en él, incluyendo su ecosistema (Livewire, Breeze, Eloquent, Artisan), supone un valor diferencial importante de cara a la incorporación al mundo profesional.

   El hecho de trabajar con un sistema de variantes de producto más complejo de lo habitual ha supuesto un reto adicional que ha enriquecido enormemente el proceso de aprendizaje, requiriendo el diseño y la implementación de una arquitectura de base de datos más sofisticada y de una capa de servicios transaccional.

## 1.4. ALCANCE DEL PROYECTO

El proyecto abarca los siguientes módulos y funcionalidades:

**Módulo de catálogo público:**
- Página de inicio con productos destacados y banner hero dinámico.
- Catálogo completo con filtros por categoría, marca, precio y estado de destacado.
- Página de detalle de producto con galería de imágenes por variante.

**Módulo de carrito y pedidos:**
- Carrito de compras persistente por usuario autenticado.
- Actualización de cantidades en tiempo real mediante Livewire.
- Proceso de pago multipaso (datos de envío → resumen → confirmación).
- Validación de stock en el momento de confirmar el pedido.

**Módulo de autenticación:**
- Registro, inicio y cierre de sesión (basado en Laravel Breeze).
- Verificación de correo electrónico.
- Edición de perfil de usuario.

**Panel de administración:**
- Dashboard con indicadores clave de negocio (KPIs).
- Gestión completa de productos con variantes y galerías.
- Gestión de categorías con imágenes.
- Gestión y seguimiento de pedidos con cambio de estado.
- Gestión de usuarios.
- Estadísticas de ventas.
- Gestión del banner hero (escritorio y móvil).

**Infraestructura:**
- Despliegue en Docker con servicios: aplicación Laravel, trabajador de colas, base de datos MySQL.
- Procesamiento de imágenes a formato WebP mediante Intervention Image.

---

# 2. ESTUDIO DE LA VIABILIDAD

## 2.1. ANÁLISIS DE LA SITUACIÓN ACTUAL

   El comercio electrónico en España ha experimentado un crecimiento sostenido en los últimos años. Según datos del Banco de España y de la Comisión Nacional de Mercados y la Competencia (CNMC), el e-commerce nacional supera los 70.000 millones de euros anuales de facturación, con una tendencia al alza impulsada por la digitalización acelerada posterior a la pandemia.

   A pesar de este contexto favorable, muchas pequeñas y medianas empresas del sector tecnológico siguen sin contar con una presencia online propia, recurriendo a marketplaces como Amazon o Wallapop o a soluciones genéricas como Shopify o PrestaShop, las cuales, si bien son funcionales, implican costes recurrentes de licencia y menor control sobre el producto y los datos del negocio.

   El sistema actual analizado —cuyo punto de partida es una implementación en PHP nativo (`ProyectoIntermodular/`)— presenta las siguientes limitaciones:

- Código sin framework, con alto acoplamiento y difícil mantenimiento.
- Ausencia de gestión de variantes de producto (solo un modelo de producto sin opciones).
- Carencia de panel de administración integrado.
- Sin sistema de gestión de imágenes optimizadas.
- Sin soporte para despliegue contenerizado.

## 2.2. DIAGNÓSTICO DE LA PROBLEMÁTICA

A partir del análisis anterior, se detectan los siguientes problemas principales:

1. **Rigidez del modelo de producto:** La tienda original solo contempla productos sin variantes, lo que impide vender artículos como smartphones con diferentes colores o capacidades de almacenamiento bajo un único producto.
2. **Ausencia de panel de administración:** Cualquier modificación en el catálogo o en los pedidos requería acceso directo a la base de datos.
3. **Nula optimización de imágenes:** Las imágenes se servían en formatos pesados, penalizando los tiempos de carga.
4. **Sin arquitectura para el mantenimiento:** El código PHP nativo mezclaba lógica de negocio con presentación, dificultando el mantenimiento y la extensión del sistema.
5. **Sin pruebas automatizadas:** No existía ningún tipo de cobertura de tests, lo que hacía los despliegues arriesgados.

## 2.3. VIABILIDAD TÉCNICA

El proyecto es técnicamente viable por las siguientes razones:

- **Laravel 12** es un framework maduro, con extensa documentación y una comunidad activa, que provee de base la arquitectura MVC, ORM (Eloquent), sistema de migraciones, autenticación, cola de trabajos y muchos otros elementos que aceleran el desarrollo.
- **Livewire 4** permite añadir interactividad al frontend sin necesidad de escribir JavaScript complejo, manteniéndose en el paradigma PHP con el que el desarrollador ya trabaja.
- **Docker** facilita que la aplicación funcione de forma idéntica en desarrollo y producción, eliminando los problemas de "en mi máquina funciona".
- **Intervention Image 4** proporciona una API sencilla para redimensionar y convertir imágenes a WebP, mejorando el rendimiento sin esfuerzo adicional.
- El desarrollador cuenta con los conocimientos necesarios en PHP, Laravel, HTML/CSS y SQL para abordar el proyecto.

## 2.4. VIABILIDAD ECONÓMICA

Para una empresa que quisiera implantar un sistema equivalente, los costes serían los siguientes:

| Concepto | Coste estimado |
|---|---|
| Servidor VPS (2 vCPU, 4 GB RAM) | 20 €/mes |
| Dominio (.es) | 10 €/año |
| Certificado SSL (Let's Encrypt) | Gratuito |
| Almacenamiento en la nube (S3 compatible) | ~5 €/mes |
| Desarrollo (estimación 280 horas × 25 €/h) | 7.000 € |
| **Total primer año** | **~7.370 €** |

Comparado con soluciones SaaS como Shopify (desde 29 $/mes + comisiones por venta), la inversión inicial es mayor pero los costes recurrentes son sensiblemente inferiores, y la empresa mantiene el control total sobre el código y los datos.

## 2.5. VIABILIDAD TEMPORAL

El desarrollo se ha planificado en las siguientes fases, con una estimación de 280 horas totales distribuidas a lo largo del curso académico 2025-2026:

| Fase | Tareas | Horas estimadas |
|---|---|---|
| Análisis y diseño | Requisitos, diseño de BD, arquitectura | 30 |
| Setup e infraestructura | Proyecto Laravel, Docker, CI | 15 |
| Módulo de autenticación | Breeze, middleware admin | 10 |
| Módulo de catálogo | Modelos, controladores, vistas | 40 |
| Variantes de producto | Diseño DB, servicio, admin | 50 |
| Carrito y checkout | CartController, CheckoutWizard | 30 |
| Panel de administración | Dashboard, CRUD, estadísticas | 50 |
| Sistema de imágenes | ImageService, WebP, galería | 20 |
| Hero Banner y extras | HeroBanner, caché | 10 |
| Pruebas | Tests unitarios e integración | 15 |
| Documentación y presentación | Memoria, slides | 10 |
| **Total** | | **280 h** |

---

# 3. ALTERNATIVAS Y SELECCIÓN DE LA SOLUCIÓN

## 3.1. ALTERNATIVAS CONSIDERADAS

Para el desarrollo del proyecto se evaluaron las siguientes alternativas tecnológicas:

### 3.1.1. ALTERNATIVA A: PLATAFORMA SAAS (SHOPIFY / PRESTASHOP)

Shopify y PrestaShop son plataformas de e-commerce listas para usar que ofrecen todos los módulos necesarios sin necesidad de desarrollo desde cero.

**Ventajas:** Rapidez de puesta en marcha, sin necesidad de infraestructura propia, soporte oficial.

**Inconvenientes:** Dependencia del proveedor, costes de licencia/comisiones recurrentes, personalización limitada, poca integración con el aprendizaje del ciclo formativo.

**Decisión:** Descartada. El propósito del proyecto es demostrar competencias de desarrollo web, no de administración de plataformas.

### 3.1.2. ALTERNATIVA B: DESARROLLO CON SYMFONY

Symfony es otro framework PHP de referencia, con mayor rigor arquitectónico que Laravel pero con una curva de aprendizaje más pronunciada.

**Ventajas:** Muy robusto, alta flexibilidad, componentes reutilizables, estándar en proyectos empresariales de gran escala.

**Inconvenientes:** Mayor complejidad de configuración, más verboso, ecosistema más pequeño en el ámbito del e-commerce, menor rapidez de desarrollo para proyectos medianos.

**Decisión:** Descartada. Para el tamaño del proyecto y los plazos disponibles, Laravel ofrece mayor productividad.

### 3.1.3. ALTERNATIVA C: DESARROLLO CON LARAVEL + INERTIA.JS + VUE.JS

Laravel con Inertia.js permite construir SPAs (Single Page Applications) utilizando Vue.js o React en el frontend, manteniendo Laravel en el backend.

**Ventajas:** Frontend muy reactivo, experiencia de usuario fluida, separación clara de responsabilidades.

**Inconvenientes:** Mayor complejidad (dos capas de desarrollo independientes), requiere conocimientos sólidos de Vue.js, mayor tamaño del bundle JavaScript.

**Decisión:** Descartada. Livewire ofrece la interactividad necesaria sin duplicar la complejidad del stack.

### 3.1.4. ALTERNATIVA D: DESARROLLO CON LARAVEL + LIVEWIRE (SOLUCIÓN ADOPTADA)

Laravel 12 con Livewire 4 permite desarrollar interfaces reactivas directamente desde PHP, sin necesidad de un framework JavaScript independiente.

**Ventajas:** Stack unificado en PHP, menor complejidad, alta productividad, soporte oficial de Laravel, excelente para formularios complejos como el checkout multipaso.

**Inconvenientes:** Para aplicaciones con interfaces muy complejas puede resultar menos performante que una SPA completa.

**Decisión:** Adoptada. Es la solución que mejor equilibra productividad, aprendizaje y adecuación al tamaño del proyecto.

## 3.2. EVALUACIÓN DE RIESGOS

| Riesgo | Probabilidad | Impacto | Mitigación |
|---|---|---|---|
| Complejidad del modelo de variantes | Alta | Alto | Diseño previo detallado del esquema de BD |
| Desbordamiento de plazos | Media | Medio | Planificación por fases con hitos claros |
| Problemas de rendimiento N+1 | Media | Medio | Eager loading sistemático en todas las consultas |
| Inconsistencias de stock al pedir | Baja | Alto | Transacciones DB y validación de stock antes de confirmar |
| Problemas de despliegue Docker | Baja | Bajo | Uso de Docker Compose con health checks |

## 3.3. SELECCIÓN DE LA SOLUCIÓN

   La solución adoptada es el desarrollo de una aplicación web completa con **Laravel 12** como framework principal, **Livewire 4** para los componentes interactivos (carrito, checkout, formularios de administración), **Tailwind CSS v4** para los estilos, **Alpine.js** para micro-interacciones ligeras en el cliente, **MySQL 8** como sistema de gestión de base de datos, e **Intervention Image 4** para el procesamiento de imágenes.

   El entorno de despliegue se gestiona mediante **Docker Compose**, con servicios separados para la aplicación, el procesador de colas y la base de datos. Los activos estáticos se compilan con **Vite**, generando bundles optimizados para producción.

---

# 4. ANÁLISIS Y DISEÑO DE LA SOLUCIÓN ADOPTADA

## 4.1. REQUISITOS FUNCIONALES

### 4.1.1. MÓDULO DE CLIENTES (PÚBLICO)

| ID | Requisito |
|---|---|
| RF-01 | El sistema permitirá visualizar una página de inicio con productos destacados y un banner dinámico. |
| RF-02 | El sistema permitirá explorar el catálogo con filtros por categoría, marca, rango de precio y estado destacado. |
| RF-03 | El sistema permitirá ver el detalle de un producto con todas sus variantes, imágenes y precio. |
| RF-04 | El sistema permitirá al usuario registrado añadir productos al carrito seleccionando una variante. |
| RF-05 | El sistema permitirá modificar las cantidades y eliminar artículos del carrito sin recargar la página. |
| RF-06 | El sistema guiará al usuario por un proceso de pago de tres pasos: datos de envío, resumen y confirmación. |
| RF-07 | El sistema validará el stock disponible antes de confirmar un pedido. |
| RF-08 | El sistema generará una referencia única para cada pedido confirmado (formato SYN-XXXXXX). |

### 4.1.2. MÓDULO DE AUTENTICACIÓN

| ID | Requisito |
|---|---|
| RF-09 | El sistema permitirá el registro de nuevos usuarios con verificación de correo. |
| RF-10 | El sistema permitirá el inicio y cierre de sesión. |
| RF-11 | El sistema permitirá al usuario editar su perfil y eliminar su cuenta. |

### 4.1.3. PANEL DE ADMINISTRACIÓN

| ID | Requisito |
|---|---|
| RF-12 | El sistema mostrará en el dashboard los KPIs principales: total de productos, categorías, pedidos pendientes, ingresos mensuales y productos con bajo stock. |
| RF-13 | El sistema permitirá al administrador crear, editar y eliminar (soft delete) productos con grupos de opciones y variantes anidadas. |
| RF-14 | El sistema permitirá subir imágenes globales y por variante de color para cada producto. |
| RF-15 | El sistema permitirá gestionar categorías con imagen asociada. |
| RF-16 | El sistema permitirá listar, filtrar y ver el detalle de todos los pedidos. |
| RF-17 | El sistema permitirá al administrador actualizar el estado de un pedido (Pendiente → Enviado → Entregado). |
| RF-18 | El sistema permitirá gestionar usuarios (crear, editar, eliminar, asignar rol). |
| RF-19 | El sistema permitirá actualizar el banner hero con imágenes para escritorio y móvil. |
| RF-20 | El sistema mostrará estadísticas de ventas. |

## 4.2. REQUISITOS NO FUNCIONALES

| ID | Requisito |
|---|---|
| RNF-01 | Las páginas públicas deben cargarse en menos de 2 segundos en condiciones normales de tráfico. |
| RNF-02 | Las imágenes deben servirse en formato WebP con calidad al 85% para optimizar el ancho de banda. |
| RNF-03 | El sistema debe usar caché para las consultas frecuentes (productos destacados 10 min, categorías 1 hora). |
| RNF-04 | Todas las escrituras multi-paso (creación de producto, confirmación de pedido) deben ejecutarse en transacciones de base de datos. |
| RNF-05 | El acceso al panel de administración debe estar restringido al rol `admin`. |
| RNF-06 | Las contraseñas deben almacenarse hasheadas con bcrypt. |
| RNF-07 | El entorno de ejecución debe ser reproducible mediante Docker. |
| RNF-08 | El código debe cumplir los estándares PSR-12 validados con Laravel Pint. |
| RNF-09 | Los mensajes de validación y la interfaz pública serán en español. |

## 4.3. CASOS DE USO

### 4.3.1. ACTORES DEL SISTEMA

- **Visitante:** Usuario no autenticado. Puede explorar el catálogo.
- **Cliente:** Usuario autenticado. Puede gestionar el carrito y realizar pedidos.
- **Administrador:** Usuario con rol `admin`. Puede gestionar todos los recursos del sistema.

### 4.3.2. CASOS DE USO PRINCIPALES

**CU-01: Explorar catálogo**
- Actor: Visitante / Cliente
- Precondición: Ninguna
- Flujo: El usuario accede a `/catalogo`, aplica filtros opcionales (categoría, marca, precio, destacado) y ordena los resultados. El sistema devuelve los productos paginados de 12 en 12.

**CU-02: Ver detalle de producto**
- Actor: Visitante / Cliente
- Precondición: Ninguna
- Flujo: El usuario selecciona un producto del catálogo. El sistema muestra el nombre, descripción, grupos de opciones (color, almacenamiento), precio de la variante seleccionada, galería de imágenes y productos relacionados de la misma categoría.

**CU-03: Añadir al carrito**
- Actor: Cliente
- Precondición: Usuario autenticado, variante seleccionada con stock > 0
- Flujo: El cliente selecciona una variante y pulsa "Añadir al carrito". Si el artículo ya existe en el carrito, se incrementa la cantidad; si no, se crea una nueva línea.

**CU-04: Realizar pedido**
- Actor: Cliente
- Precondición: Carrito con al menos un artículo
- Flujo normal:
  1. Cliente accede al checkout.
  2. Introduce datos de envío (nombre, dirección, ciudad, CP, provincia, teléfono).
  3. Revisa el resumen del pedido.
  4. Confirma el pedido.
  5. El sistema verifica el stock, crea el pedido, decrementa el stock y vacía el carrito.
  6. Se muestra la confirmación con referencia SYN-XXXXXX.
- Flujo alternativo: Si no hay stock suficiente, el sistema muestra un error y no crea el pedido.

**CU-05: Gestionar productos (admin)**
- Actor: Administrador
- Precondición: Sesión de administrador activa
- Flujo: El administrador crea o edita un producto definiendo grupos de opciones (p.ej., "Color" con valores "Negro" y "Blanco") y por cada combinación de valores una variante con precio y stock. Sube imágenes globales del producto e imágenes específicas por color.

**CU-06: Actualizar estado de pedido (admin)**
- Actor: Administrador
- Flujo: El administrador accede al listado de pedidos, filtra por estado o fecha, abre el detalle y actualiza el estado. Los estados válidos son: Pendiente → Enviado → Entregado (el estado Pagado se asigna automáticamente al confirmar el checkout).

## 4.4. ARQUITECTURA DEL SISTEMA

El sistema sigue el patrón **MVC (Modelo-Vista-Controlador)** propio de Laravel, extendido con una **capa de servicios** para la lógica de negocio transaccional.

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENTE (Navegador)                   │
│              Tailwind CSS + Alpine.js                    │
└─────────────────────┬───────────────────────────────────┘
                      │ HTTP / Livewire WebSocket
┌─────────────────────▼───────────────────────────────────┐
│                   CAPA DE RUTAS                          │
│              routes/web.php (Laravel)                    │
├─────────────────────────────────────────────────────────┤
│              MIDDLEWARE                                  │
│     auth | verified | admin (AdminMiddleware)            │
├───────────────────────┬─────────────────────────────────┤
│    CONTROLADORES      │    COMPONENTES LIVEWIRE          │
│  Http/Controllers/    │    app/Livewire/                 │
│  Admin/ + Public/     │    CheckoutWizard, StoreCart     │
├───────────────────────┴─────────────────────────────────┤
│                   CAPA DE SERVICIOS                      │
│  ProductService | CategoryService | UserService          │
│  OrderService | ImageService | HeroBannerService         │
├─────────────────────────────────────────────────────────┤
│                      MODELOS                             │
│  Eloquent ORM: Producto, Variante, Categoria,            │
│  Pedido, Carrito, User, HeroBanner, ...                  │
├─────────────────────────────────────────────────────────┤
│                  BASE DE DATOS                           │
│                 MySQL 8.0 (Docker)                       │
└─────────────────────────────────────────────────────────┘
```

**Flujo de una petición típica:**

1. El navegador envía una petición HTTP a Laravel.
2. El router la dirige al controlador o componente Livewire correspondiente, pasando por los middlewares.
3. El controlador delega la lógica de negocio compleja en el servicio correspondiente.
4. El servicio interactúa con los modelos Eloquent, que a su vez generan las consultas SQL a MySQL.
5. El controlador recibe los datos y los pasa a la vista Blade.
6. La vista renderiza el HTML que se devuelve al navegador.

## 4.5. DISEÑO DE LA BASE DE DATOS

### 4.5.1. ESQUEMA RELACIONAL

El modelo de datos comprende las siguientes tablas principales:

**TABLA: `users`**

| Columna | Tipo | Descripción |
|---|---|---|
| id | BIGINT PK | Clave primaria |
| name | VARCHAR(255) | Nombre completo |
| email | VARCHAR(255) UNIQUE | Correo electrónico |
| password | VARCHAR(255) | Hash bcrypt |
| rol | VARCHAR(50) | Rol del usuario ('user' / 'admin') |
| email_verified_at | TIMESTAMP NULL | Verificación de email |

**TABLA: `categorias`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_categoria | BIGINT PK | Clave primaria |
| nombre | VARCHAR(100) UNIQUE | Nombre de la categoría |
| imagen | VARCHAR(255) NULL | Ruta de imagen en storage |
| deleted_at | TIMESTAMP NULL | Soft delete |

**TABLA: `productos`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_producto | BIGINT PK | Clave primaria |
| nombre | VARCHAR(150) | Nombre del producto |
| descripcion | TEXT NULL | Descripción larga |
| id_categoria | BIGINT FK NULL | Referencia a categorias |
| brand | VARCHAR(100) NULL | Marca del producto |
| precio_base | DECIMAL(10,2) | Precio base de fallback |
| destacado | BOOLEAN | Producto en portada |
| deleted_at | TIMESTAMP NULL | Soft delete |

**TABLA: `grupos_opcion_producto`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_grupo | BIGINT PK | Clave primaria |
| id_producto | BIGINT FK | Referencia a productos |
| nombre | VARCHAR(50) | Nombre del grupo (ej: "Color") |
| tipo | VARCHAR(20) | Tipo de selector ('color' / 'texto') |
| orden | INT | Orden de presentación |

**TABLA: `valores_opcion_producto`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_valor | BIGINT PK | Clave primaria |
| id_grupo | BIGINT FK | Referencia al grupo de opciones |
| nombre | VARCHAR(50) | Etiqueta del valor (ej: "Negro") |
| hex_code | VARCHAR(10) NULL | Color hexadecimal (solo tipo 'color') |
| imagen | VARCHAR(255) NULL | Imagen del color |
| precio_extra | DECIMAL(10,2) | Incremento sobre el precio base |
| orden | INT | Orden de presentación |

**TABLA: `variantes`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_variante | BIGINT PK | Clave primaria |
| id_producto | BIGINT FK | Referencia a productos |
| precio | DECIMAL(10,2) | Precio de esta variante |
| stock | INT | Unidades disponibles |
| sku | VARCHAR(100) UNIQUE NULL | Código de referencia |
| deleted_at | TIMESTAMP NULL | Soft delete |

**TABLA: `variante_valores` (tabla pivote)**

| Columna | Tipo | Descripción |
|---|---|---|
| id | BIGINT PK | Clave primaria |
| id_variante | BIGINT FK | Referencia a variantes |
| id_valor | BIGINT FK | Referencia a valores_opcion_producto |
| UNIQUE | (id_variante, id_valor) | Una variante no repite valores |

**TABLA: `imagen_productos`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_imagen | BIGINT PK | Clave primaria |
| id_producto | BIGINT FK | Referencia a productos |
| id_valor | BIGINT FK NULL | NULL = imagen global; valor = imagen del color |
| ruta | VARCHAR(255) | Ruta del archivo WebP en storage |
| orden | INT | Orden de presentación en la galería |

**TABLA: `carrito`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_carrito | BIGINT PK | Clave primaria |
| id_usuario | BIGINT FK | Referencia al usuario |
| id_variante | BIGINT FK | Referencia a la variante |
| cantidad | INT | Unidades en el carrito |
| UNIQUE | (id_usuario, id_variante) | Un usuario no duplica variantes |

**TABLA: `pedidos`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_pedido | BIGINT PK | Clave primaria |
| id_usuario | BIGINT FK | Referencia al usuario |
| fecha | DATETIME | Fecha del pedido |
| total | DECIMAL(10,2) | Importe total |
| estado | ENUM | pendiente / pagado / enviado / entregado |
| nombre_envio | VARCHAR(150) NULL | Nombre del destinatario |
| direccion | VARCHAR(255) NULL | Dirección de entrega |
| ciudad | VARCHAR(100) NULL | Ciudad |
| codigo_postal | VARCHAR(10) NULL | Código postal |
| provincia | VARCHAR(100) NULL | Provincia |
| telefono | VARCHAR(20) NULL | Teléfono de contacto |

**TABLA: `detalle_pedido`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_detalle | BIGINT PK | Clave primaria |
| id_pedido | BIGINT FK | Referencia al pedido |
| id_variante | BIGINT FK | Referencia a la variante comprada |
| cantidad | INT | Cantidad comprada |
| precio_unitario | DECIMAL(10,2) | Precio en el momento de la compra |

**TABLA: `hero_banners`**

| Columna | Tipo | Descripción |
|---|---|---|
| id_banner | BIGINT PK | Clave primaria |
| imagen_desktop | VARCHAR(255) | Ruta imagen escritorio |
| imagen_mobile | VARCHAR(255) | Ruta imagen móvil |
| enlace | VARCHAR(255) NULL | URL destino del banner |
| titulo | VARCHAR(100) NULL | Texto alternativo |
| activo | BOOLEAN | Visible en la página de inicio |
| orden | INT | Prioridad si hay varios banners |

### 4.5.2. DECISIONES DE DISEÑO DE LA BASE DE DATOS

- **Claves primarias personalizadas:** Todas las tablas de dominio usan claves como `id_producto`, `id_carrito`, etc. en lugar del `id` convencional de Laravel, en coherencia con el esquema original del sistema legacy. Esto requiere que todas las relaciones Eloquent especifiquen explícitamente las claves foráneas y propietarias.

- **Soft deletes en entidades de catálogo:** `Producto`, `Variante` y `Categoria` implementan soft delete para preservar el historial de pedidos que referencia variantes y productos eliminados.

- **Sin soft deletes en datos transaccionales:** `Carrito`, `Pedido` y `DetallePedido` no usan soft delete, pues el borrado de estas entidades debe ser definitivo o simplemente no se produce.

- **Separación imagen global vs imagen por color:** La tabla `imagen_productos` usa `id_valor NULL` para imágenes globales y `id_valor != NULL` para imágenes asociadas a un valor de opción de tipo color. Esto permite una galería dinámica según la selección del usuario.

## 4.6. DISEÑO DE LA INTERFAZ

### 4.6.1. PALETA DE COLORES Y TIPOGRAFÍA

La interfaz pública usa una paleta oscura con acentos en negro y grises claros, evocando una estética tecnológica premium. Las fuentes son del sistema operativo (sans-serif nativas) para maximizar el rendimiento de carga.

### 4.6.2. PÁGINAS PRINCIPALES

- **Inicio:** Hero banner a pantalla completa + sección de productos destacados en grid de 4 columnas + sección de categorías.
- **Catálogo:** Sidebar de filtros (izquierda) + grid de productos (derecha) con paginación.
- **Detalle de producto:** Galería de imágenes con selector por color, selector de opciones, precio dinámico, botón de añadir al carrito.
- **Carrito:** Tabla de artículos con controles de cantidad en tiempo real (Livewire) y resumen de totales.
- **Checkout:** Formulario multipaso con indicador de progreso.

### 4.6.3. PANEL DE ADMINISTRACIÓN

- Barra lateral de navegación fija con secciones: Dashboard, Productos, Categorías, Pedidos, Usuarios, Estadísticas.
- Dashboard con tarjetas de KPI, tabla de últimos pedidos y vista previa del banner.
- Formularios de producto con campos anidados dinámicos para grupos de opciones y variantes.

---

# 5. IMPLEMENTACIÓN

## 5.1. ENTORNO DE DESARROLLO

Las herramientas y versiones utilizadas durante el desarrollo son:

| Herramienta | Versión |
|---|---|
| PHP | 8.2 |
| Laravel | 12.x |
| Livewire | 4.x |
| Tailwind CSS | 4.x |
| Alpine.js | 3.x |
| Vite | 6.x |
| MySQL | 8.0 |
| Docker / Docker Compose | 27.x |
| Intervention Image | 4.x |
| Node.js | 22.x |
| Composer | 2.x |

Los comandos principales del proyecto son:

```bash
# Instalar el entorno completo (primera vez)
composer setup

# Iniciar todos los servicios en desarrollo
composer dev

# Ejecutar los tests
composer test

# Formatear el código con Pint
vendor/bin/pint

# Recargar la BD con datos de ejemplo
php artisan migrate:fresh --seed
```

## 5.2. ESTRUCTURA DEL PROYECTO

La estructura de directorios del proyecto sigue las convenciones de Laravel, con las siguientes adiciones notables:

```
synapse-laravel/
├── app/
│   ├── Enums/
│   │   └── OrderStatus.php          # Enum de estados de pedido
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/               # Controladores del panel de administración
│   │   │   │   ├── AdminCategoryController.php
│   │   │   │   ├── AdminHeroBannerController.php
│   │   │   │   ├── AdminOrderController.php
│   │   │   │   ├── AdminProductController.php
│   │   │   │   └── AdminUserController.php
│   │   │   ├── CartController.php
│   │   │   ├── HomeController.php
│   │   │   └── ProductController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php  # Comprueba rol === 'admin'
│   │   └── Requests/
│   │       ├── Admin/               # Form requests de administración
│   │       └── Cart/                # Form requests del carrito
│   ├── Livewire/
│   │   ├── CheckoutWizard.php       # Asistente de compra multipaso
│   │   └── StoreCart.php            # Carrito en tiempo real
│   ├── Models/
│   │   ├── Carrito.php
│   │   ├── Categoria.php
│   │   ├── DetallePedido.php
│   │   ├── GrupoOpcionProducto.php
│   │   ├── HeroBanner.php
│   │   ├── ImagenProducto.php
│   │   ├── Pedido.php
│   │   ├── Producto.php
│   │   ├── User.php
│   │   ├── ValorOpcionProducto.php
│   │   └── Variante.php
│   └── Services/
│       ├── CategoryService.php
│       ├── HeroBannerService.php
│       ├── ImageService.php
│       ├── OrderService.php
│       ├── ProductService.php
│       └── UserService.php
├── database/
│   ├── migrations/                  # Una migración por tabla
│   └── seeders/
│       └── DatabaseSeeder.php       # Datos de ejemplo
├── resources/
│   ├── css/app.css                  # Tailwind v4 (configuración inline)
│   ├── js/app.js                    # Alpine.js y Livewire
│   └── views/
│       ├── admin/                   # Vistas del panel de administración
│       ├── auth/                    # Vistas de autenticación (Breeze)
│       ├── components/              # Componentes Blade reutilizables
│       ├── livewire/                # Vistas de componentes Livewire
│       ├── home.blade.php
│       ├── catalog.blade.php
│       └── product-show.blade.php
├── routes/
│   └── web.php                      # Todas las rutas de la aplicación
├── .docker/                         # Configuración específica de Docker
├── docker-compose.yml
├── Dockerfile
└── Makefile                         # Atajos para comandos Docker
```

## 5.3. CAPA DE DATOS: MODELOS Y MIGRACIONES

### 5.3.1. MODELO PRODUCTO

El modelo `Producto` es el núcleo del catálogo. Implementa `SoftDeletes` y define relaciones explícitas con todos sus componentes:

```php
// Relaciones principales
public function variantes()
{
    return $this->hasMany(Variante::class, 'id_producto', 'id_producto');
}

public function gruposOpciones()
{
    return $this->hasMany(GrupoOpcionProducto::class, 'id_producto', 'id_producto');
}

public function imagenes()
{
    // Solo imágenes globales (sin asociación a color)
    return $this->hasMany(ImagenProducto::class, 'id_producto', 'id_producto')
                ->whereNull('id_valor');
}
```

Dos atributos computados destacan en este modelo:

- `getPrecioAttribute()`: Devuelve el precio mínimo entre todas las variantes activas, o el `precio_base` como fallback si el producto no tiene variantes.
- `getImagenPrincipalAttribute()`: Implementa una cascada de resolución: imagen global de galería → imagen del primer valor de color → imagen del asset estático según el nombre del producto.

### 5.3.2. SISTEMA DE VARIANTES (POST-REFACTOR 2026-05-13)

El sistema de variantes fue refactorizado durante el desarrollo para superar las limitaciones del diseño original. El esquema previo tenía columnas fijas `color` y `almacenamiento` en la tabla `variantes_producto`, lo que impedía añadir nuevos atributos sin modificar el esquema.

El nuevo diseño de 4 tablas (`grupos_opcion_producto`, `valores_opcion_producto`, `variantes`, `variante_valores`) permite que cada producto tenga sus propios grupos de opciones con cualquier número de valores.

El proceso de creación de un producto con variantes en `ProductService::createProduct` sigue estos pasos dentro de una transacción:

1. Crear el registro en `productos`.
2. Crear los grupos de opciones del producto.
3. Crear los valores de cada grupo, guardando un mapa `{grupoIndex}_{valorIndex} → id_valor`.
4. Crear cada variante con su precio y stock.
5. Asociar cada variante a sus valores mediante la tabla pivote `variante_valores`.
6. Procesar y almacenar las imágenes globales y por color.

### 5.3.3. SCOPE DE FILTRADO

Los modelos principales implementan un scope `scopeFilter` que acepta un array de parámetros y aplica los filtros correspondientes:

```php
// Ejemplo simplificado de Producto::scopeFilter
public function scopeFilter(Builder $query, array $filters): Builder
{
    if ($search = $filters['search'] ?? null) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('brand', 'like', "%$search%");
        });
    }
    if ($categoria = $filters['id_categoria'] ?? null) {
        $query->where('id_categoria', $categoria);
    }
    if (isset($filters['destacado'])) {
        $query->where('destacado', true);
    }
    return $query;
}
```

## 5.4. CAPA DE SERVICIOS

### 5.4.1. PRODUCTSERVICE

`ProductService` centraliza toda la lógica de creación y actualización de productos. Al encapsular estas operaciones en transacciones de base de datos, garantiza que si cualquier paso falla (subida de imagen, creación de variante, etc.), la operación entera se revierte y no quedan datos huérfanos.

También es responsable de invalidar las claves de caché relevantes tras cada modificación, asegurando que la página de inicio muestre datos actualizados.

### 5.4.2. IMAGESERVICE

`ImageService` abstrae todo el procesamiento de imágenes:

```php
public function storeAsWebp(
    UploadedFile $file,
    string $directory,
    ?int $quality = 85
): string {
    $image = Image::read($file->getRealPath());
    $webpBinary = $image->toWebp($quality);
    $filename = Str::uuid() . '.webp';
    Storage::disk('public')->put($directory . '/' . $filename, $webpBinary);
    return $directory . '/' . $filename;
}
```

Todas las imágenes subidas (productos, categorías, banners) pasan por este servicio y se convierten a WebP, con un nombre aleatorio basado en UUID para evitar colisiones.

### 5.4.3. ORDERSERVICE

`OrderService::changeStatus` aplica las transiciones de estado válidas, delegando en el enum `OrderStatus` la definición de qué estados son asignables manualmente por el administrador.

## 5.5. CAPA DE PRESENTACIÓN: CONTROLADORES Y RUTAS

### 5.5.1. ORGANIZACIÓN DE RUTAS

Las rutas se definen en `routes/web.php` agrupadas por contexto:

```php
// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalog.index');

// Rutas de cliente autenticado
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/add', [CartController::class, 'add'])->name('cart.add');
    // ...
});

// Rutas de administración
Route::middleware(['auth', 'admin'])
    ->prefix('dashboard')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class);
        // ...
    });
```

### 5.5.2. MIDDLEWARE DE ADMINISTRACIÓN

`AdminMiddleware` comprueba que el usuario autenticado tiene el rol `admin`:

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->rol !== 'admin') {
        abort(403, 'Acceso no autorizado.');
    }
    return $next($request);
}
```

### 5.5.3. DASHBOARDCONTROLLER

El dashboard agrega datos de múltiples modelos para mostrar los KPIs del negocio:

- Total de productos activos.
- Total de categorías.
- Pedidos en estado `pendiente`.
- Ingresos del mes en curso (suma de `total` de pedidos con estado `pagado`, `enviado` o `entregado` del mes actual).
- Productos con stock ≤ 5 unidades en alguna variante (alerta de bajo stock).
- Los 5 últimos pedidos con información del cliente.

## 5.6. COMPONENTES LIVEWIRE

### 5.6.1. CHECKOUTWIZARD

`CheckoutWizard` gestiona el proceso de pago en tres pasos sin recargas de página:

- **Paso 1 (Envío):** Formulario con campos de dirección, validados en tiempo real con el método `updated($propertyName)`.
- **Paso 2 (Resumen):** Vista de solo lectura con los artículos del carrito y el total calculado.
- **Paso 3 (Confirmación):** El método `confirm()` ejecuta dentro de una transacción DB:
  1. Verifica que el stock de cada variante es suficiente.
  2. Crea el registro en `pedidos`.
  3. Crea los registros en `detalle_pedido`.
  4. Decrementa el stock de cada variante.
  5. Vacía el carrito del usuario.
  6. Avanza al paso 3 mostrando la referencia del pedido.

### 5.6.2. STORECART

`StoreCart` permite al usuario modificar el carrito (incrementar, decrementar, eliminar) directamente desde la vista del carrito sin recargar la página. Los métodos `increment`, `decrement` y `remove` modifican la base de datos y Livewire renderiza automáticamente la vista actualizada.

## 5.7. SISTEMA DE IMÁGENES Y ALMACENAMIENTO

Las imágenes se almacenan en el disco `public` de Laravel (`storage/app/public`), accesibles mediante `storage_path()` y el enlace simbólico `public/storage`. La estructura de directorios es:

```
storage/app/public/
├── products/{id_producto}/
│   ├── gallery/          # Imágenes globales del producto
│   ├── options/          # Imágenes de valores de opciones
│   └── colors/{id_valor}/ # Galería por variante de color
├── categories/            # Imágenes de categorías
└── hero/                  # Imágenes del banner hero
```

Todos los archivos se almacenan en formato WebP para optimizar el tamaño y el rendimiento de carga.

## 5.8. SISTEMA DE AUTENTICACIÓN Y AUTORIZACIÓN

La autenticación se construye sobre **Laravel Breeze**, que proporciona registro, login, logout, verificación de email y edición de perfil con una interfaz Blade lista para usar.

La autorización se implementa en dos niveles:
- **Middleware `auth`:** Garantiza que el usuario ha iniciado sesión.
- **Middleware `admin`:** Verifica adicionalmente que el usuario tiene `rol === 'admin'`.

El modelo `User` expone el método `isAdmin()` para facilitar comprobaciones en las vistas:

```php
public function isAdmin(): bool
{
    return $this->rol === 'admin';
}
```

## 5.9. DESPLIEGUE CON DOCKER

La aplicación se conteneriza mediante Docker Compose con tres servicios:

**Servicio `app` (PHP-FPM + Nginx):** Imagen multi-etapa que primero compila los assets Node.js y luego los incorpora a la imagen PHP final. Esto garantiza que el contenedor de producción no lleva Node.js consigo.

**Servicio `worker`:** Misma imagen que `app`, arranca el comando `php artisan queue:listen` para procesar trabajos en cola (envío de emails, etc.).

**Servicio `db` (MySQL 8.0):** Base de datos con volumen persistente. Incluye un health check que evita que el servicio `app` arranque antes de que MySQL esté listo.

El `Makefile` proporciona atajos para las operaciones más habituales en el entorno Docker:

```makefile
up:       # docker compose up -d
down:     # docker compose down
fresh:    # php artisan migrate:fresh --seed
shell:    # docker compose exec app bash
logs:     # docker compose logs -f app
```

---

# 6. PRUEBAS

## 6.1. ESTRATEGIA DE PRUEBAS

La estrategia de pruebas del proyecto combina tres niveles:

1. **Pruebas unitarias:** Verifican el comportamiento de clases aisladas (modelos, servicios, enums).
2. **Pruebas de integración (Feature Tests):** Verifican que los flujos completos de la aplicación (peticiones HTTP, operaciones de base de datos) funcionan correctamente.
3. **Pruebas manuales:** Verificación visual del comportamiento en el navegador.

El framework de pruebas es **PHPUnit 11**, integrado con el comando `composer test` que limpia la caché de configuración antes de ejecutar la suite. Los tests usan SQLite en memoria (`:memory:`), configurado en `phpunit.xml`, eliminando la necesidad de una base de datos de prueba separada.

## 6.2. PRUEBAS UNITARIAS

Las pruebas unitarias cubren la lógica de negocio más crítica:

**Test del enum OrderStatus:**
- Verifica que el método `values()` devuelve los cuatro estados definidos.
- Verifica que `manuallyAssignable()` excluye el estado `pagado` (que solo se asigna automáticamente).

**Test de modelos:**
- Verificación de que `Producto::getPrecioAttribute` devuelve el precio mínimo de variantes activas.
- Verificación de que `User::isAdmin()` devuelve `true` solo cuando `rol === 'admin'`.

## 6.3. PRUEBAS DE INTEGRACIÓN (FEATURE TESTS)

Las pruebas de integración se organizan en la carpeta `tests/Feature/Admin/` y cubren los controladores del panel de administración:

**Pruebas de gestión de productos:**
- Un administrador puede acceder al listado de productos.
- Un administrador puede crear un producto simple (sin variantes).
- Un administrador puede editar el nombre de un producto existente.
- Un administrador puede eliminar un producto (soft delete).
- Un usuario sin rol de administrador recibe un 403 al intentar acceder al panel.

**Pruebas de gestión de pedidos:**
- El dashboard carga correctamente con las estadísticas.
- El listado de pedidos se puede filtrar por estado.
- El administrador puede cambiar el estado de un pedido.

**Pruebas del carrito:**
- Un usuario autenticado puede añadir una variante al carrito.
- Añadir la misma variante dos veces incrementa la cantidad en lugar de duplicar la línea.
- Un usuario no autenticado es redirigido al login al intentar acceder al carrito.

## 6.4. PRUEBAS MANUALES

Se ejecutaron pruebas manuales en los siguientes escenarios:

| Escenario | Resultado |
|---|---|
| Registro de nuevo usuario | OK |
| Inicio de sesión con credenciales incorrectas | Error mostrado correctamente |
| Explorar catálogo con filtros combinados | OK |
| Añadir producto al carrito y modificar cantidad | OK (Livewire sin recarga) |
| Proceso de checkout completo | OK |
| Intento de pedir con stock insuficiente | Error mostrado, pedido no creado |
| Acceso al panel de administración sin rol admin | 403 correcto |
| Crear producto con variantes y subir imágenes | OK, imágenes convertidas a WebP |
| Actualizar estado de pedido en admin | OK |
| Actualizar banner hero | OK, caché invalidada |
| Responsive en móvil (Chrome DevTools) | OK |

---

# 7. COSTES Y PRESUPUESTO

## 7.1. COSTES DE DESARROLLO

El coste de desarrollo se calcula en base al tiempo invertido y a una tarifa horaria de referencia para un desarrollador web junior/medio en España:

| Fase | Horas | Tarifa (€/h) | Subtotal |
|---|---|---|---|
| Análisis y diseño | 30 | 25 € | 750 € |
| Setup e infraestructura | 15 | 25 € | 375 € |
| Módulo de autenticación | 10 | 25 € | 250 € |
| Módulo de catálogo público | 40 | 25 € | 1.000 € |
| Sistema de variantes de producto | 50 | 25 € | 1.250 € |
| Carrito y checkout | 30 | 25 € | 750 € |
| Panel de administración | 50 | 25 € | 1.250 € |
| Sistema de imágenes | 20 | 25 € | 500 € |
| Hero Banner y caché | 10 | 25 € | 250 € |
| Pruebas | 15 | 25 € | 375 € |
| Documentación | 10 | 25 € | 250 € |
| **Total desarrollo** | **280 h** | | **7.000 €** |

## 7.2. COSTES DE INFRAESTRUCTURA

Los costes de infraestructura para el primer año de operación de la tienda:

| Concepto | Coste mensual | Coste anual |
|---|---|---|
| VPS (2 vCPU, 4 GB RAM, 80 GB SSD) | 20 € | 240 € |
| Dominio (.es) | — | 10 € |
| Certificado SSL (Let's Encrypt) | Gratuito | 0 € |
| Almacenamiento adicional (backups S3) | 5 € | 60 € |
| CDN para activos estáticos (Cloudflare gratis) | 0 € | 0 € |
| **Total infraestructura (año 1)** | | **310 €** |

## 7.3. COSTES DE MANTENIMIENTO

El mantenimiento anual incluye actualizaciones de seguridad, nuevas funcionalidades menores y soporte:

| Concepto | Horas anuales estimadas | Tarifa | Subtotal |
|---|---|---|---|
| Actualizaciones de dependencias y seguridad | 10 h | 30 € | 300 € |
| Corrección de incidencias | 15 h | 30 € | 450 € |
| Pequeñas mejoras y ajustes | 20 h | 30 € | 600 € |
| **Total mantenimiento anual** | **45 h** | | **1.350 €** |

## 7.4. PRESUPUESTO TOTAL

| Concepto | Importe |
|---|---|
| Desarrollo inicial | 7.000 € |
| Infraestructura (año 1) | 310 € |
| Mantenimiento (año 1) | 1.350 € |
| **Total primer año** | **8.660 €** |
| Años siguientes (infraestructura + mantenimiento) | ~1.660 €/año |

**Comparativa con alternativas SaaS:**

| Solución | Coste año 1 | Coste año 2+ | Control de datos |
|---|---|---|---|
| Shopify Basic (29 $/mes + 2% comisiones) | ~700 € + comisiones | ~700 € + comisiones | No |
| PrestaShop hosting gestionado | ~1.000 € | ~700 €/año | Parcial |
| **Synapse (desarrollo propio)** | **8.660 €** | **1.660 €/año** | **Sí, total** |

La inversión inicial en desarrollo propio se amortiza a lo largo del tiempo, siendo rentable a partir del tercer o cuarto año de operación en comparación con soluciones SaaS, especialmente si se consideran volúmenes de ventas que disparan las comisiones de las plataformas externas.

---

# 8. CONCLUSIONES

## 8.1. GRADO DE CONSECUCIÓN DE OBJETIVOS

Todos los objetivos planteados en la fase de análisis han sido alcanzados:

| Objetivo | Estado |
|---|---|
| Aplicación de e-commerce funcional y completa | Completado |
| Integración de tecnologías frontend y backend modernas | Completado |
| Sistema de variantes flexible (grupos de opciones dinámicos) | Completado |
| Panel de administración completo | Completado |
| Pruebas automatizadas y arquitectura por servicios | Completado |
| Despliegue contenerizado con Docker | Completado |

La funcionalidad más compleja y que mayor esfuerzo supuso fue el diseño e implementación del sistema de variantes flexible. Requirió múltiples iteraciones en el diseño del esquema de base de datos y en la lógica del servicio de productos, pero el resultado final es significativamente más potente y extensible que cualquier implementación con columnas fijas.

## 8.2. DIFICULTADES ENCONTRADAS

**1. Modelo de variantes anidadas en los formularios:**
Gestionar formularios con arrays anidados (grupos → valores → variantes) en PHP/Blade presentó dificultades iniciales de indexación. Se resolvió mediante el uso de un mapa `valorIdMap` en `ProductService` que traduce los índices del formulario a IDs reales de base de datos.

**2. Caché y consistencia de datos:**
La caché agresiva en la página de inicio provocaba que los cambios en los productos no se reflejasen inmediatamente. Se solucionó invocando `ProductService::forgetProductCaches()` al final de cada operación de escritura sobre el catálogo.

**3. Relaciones Eloquent con claves no convencionales:**
Al usar claves primarias personalizadas (`id_producto`, etc.) en lugar del `id` convencional de Laravel, fue necesario especificar explícitamente todas las claves en las relaciones Eloquent, lo que generó errores difíciles de depurar hasta comprender el comportamiento del ORM con claves no estándar.

**4. Transaccionalidad del checkout:**
Garantizar la atomicidad del proceso de creación de pedido (verificar stock, crear pedido, crear detalles, decrementar stock, vaciar carrito) fue un desafío que requirió un diseño cuidadoso usando `DB::transaction` y la gestión de excepciones.

## 8.3. POSIBLES AMPLIACIONES

Las siguientes funcionalidades quedan fuera del alcance del proyecto actual pero representan ampliaciones naturales del sistema:

1. **Pasarela de pago real:** Integración con Stripe o Redsys para procesar pagos con tarjeta o Bizum.
2. **Gestión de devoluciones:** Flujo de solicitud y aprobación de devoluciones de pedidos.
3. **Sistema de reseñas:** Permitir a los clientes valorar los productos tras la compra.
4. **Programa de descuentos y cupones:** Módulo de gestión de códigos de descuento con condiciones configurables.
5. **Notificaciones por email:** Envío de emails transaccionales (confirmación de pedido, cambio de estado) usando el sistema de colas de Laravel y Mailgun/SES.
6. **Internacionalización (i18n):** Soporte multi-idioma usando el sistema de localización de Laravel.
7. **API REST:** Exposición de los datos del catálogo mediante una API para aplicaciones móviles.
8. **Búsqueda avanzada:** Integración con Meilisearch (o Laravel Scout) para búsquedas full-text rápidas.

## 8.4. VALORACIÓN PERSONAL

   Este proyecto ha representado el mayor reto técnico abordado durante el ciclo formativo, tanto en términos de complejidad como de volumen de trabajo. La necesidad de integrar múltiples tecnologías —Laravel, Livewire, Tailwind CSS, Alpine.js, Docker, Intervention Image— de forma coherente en un sistema funcional ha exigido una planificación rigurosa y una comprensión profunda de cada herramienta.

   El resultado me genera una satisfacción considerable. Synapse es una aplicación que podría ser desplegada en un entorno real con mínimas modificaciones, lo que valida que los conocimientos adquiridos en el ciclo son directamente aplicables al mercado laboral.

   El proceso de diseño de la arquitectura de variantes fue especialmente enriquecedor. El hecho de haber partido de un sistema legacy con limitaciones claras y haberlo reemplazado con un diseño extensible y bien fundamentado en el modelo relacional ha consolidado mi comprensión del diseño de bases de datos de una manera que ningún ejercicio académico aislado podría haber logrado.

   En retrospectiva, hubiera dedicado más tiempo desde el inicio a la cobertura de pruebas automatizadas, pues las refactorizaciones intermedias (especialmente el refactor del sistema de variantes) habrían sido más seguras con una suite de tests consolidada. Es una lección valiosa para proyectos futuros.

---

# 9. BIBLIOGRAFÍA

**Documentación oficial:**

1. Laravel. *Laravel 12.x Documentation*. Disponible en: https://laravel.com/docs/12.x

2. Livewire. *Livewire 4.x Documentation*. Disponible en: https://livewire.laravel.com/docs

3. Tailwind Labs. *Tailwind CSS v4 Documentation*. Disponible en: https://tailwindcss.com/docs

4. The PHP Group. *PHP 8.2 Manual*. Disponible en: https://www.php.net/manual/es/

5. Intervention Image. *Intervention Image 4.x Documentation*. Disponible en: https://image.intervention.io/v4

6. Docker Inc. *Docker Compose Documentation*. Disponible en: https://docs.docker.com/compose/

**Libros y recursos:**

7. Stauffer, Matt. *Laravel: Up & Running* (3.ª ed.). O'Reilly Media, 2023.

8. Otwell, Taylor. *Screencasts – Laracasts*. Jeffrey Way. Disponible en: https://laracasts.com

9. Mozilla Developer Network. *MDN Web Docs*. Disponible en: https://developer.mozilla.org/es/

10. Fowler, Martin. *Patterns of Enterprise Application Architecture*. Addison-Wesley, 2002.

**Recursos de consulta:**

11. Stack Overflow. *Community Q&A*. Disponible en: https://stackoverflow.com

12. Packagist. *The PHP Package Repository*. Disponible en: https://packagist.org

13. MySQL. *MySQL 8.0 Reference Manual*. Oracle Corporation. Disponible en: https://dev.mysql.com/doc/refman/8.0/en/

---

# 10. GLOSARIO

**Alpine.js:** Framework JavaScript minimalista que permite añadir comportamiento interactivo directamente en el HTML, sin necesidad de un framework completo como Vue o React.

**Artisan:** Interfaz de línea de comandos de Laravel que proporciona utilidades para el desarrollo (migraciones, seeders, generación de clases, etc.).

**Blade:** Motor de plantillas de Laravel que permite incrustar código PHP en el HTML de forma elegante.

**bcrypt:** Algoritmo criptográfico de hash utilizado por Laravel para almacenar contraseñas de forma segura.

**Cache (Caché):** Almacenamiento temporal de datos de consulta frecuente para reducir la carga en la base de datos y mejorar el tiempo de respuesta.

**CRUD:** Acrónimo de Create, Read, Update, Delete. Las cuatro operaciones básicas sobre datos persistentes.

**Docker:** Plataforma de contenerización que permite empaquetar una aplicación y todas sus dependencias en un contenedor reproducible.

**Docker Compose:** Herramienta para definir y ejecutar aplicaciones Docker multi-contenedor mediante un fichero YAML de configuración.

**E-commerce:** Comercio electrónico. Compraventa de productos o servicios a través de Internet.

**Eager Loading:** Técnica de Eloquent para cargar relaciones de base de datos de forma anticipada, evitando el problema de las consultas N+1.

**Eloquent:** ORM (Object-Relational Mapper) propio de Laravel que permite interactuar con la base de datos mediante objetos PHP en lugar de SQL directo.

**Enum:** Tipo de dato que define un conjunto fijo de valores constantes. En este proyecto, `OrderStatus` es un enum de PHP 8.1 con los estados del pedido.

**Form Request:** Clase de Laravel que encapsula la lógica de validación de un formulario, manteniendo los controladores limpios.

**Hero Banner:** Elemento visual de gran tamaño que ocupa la parte superior de la página de inicio, habitualmente con imagen y texto destacado.

**KPI:** Key Performance Indicator. Indicador clave de rendimiento. En el dashboard de la aplicación, métricas como ingresos mensuales o pedidos pendientes.

**Laravel:** Framework PHP de código abierto, de arquitectura MVC, orientado a la productividad del desarrollador.

**Livewire:** Framework de Laravel que permite crear componentes dinámicos del lado del servidor sin necesidad de JavaScript personalizado.

**Middleware:** Capa de software que se ejecuta entre la petición HTTP y el controlador, permitiendo filtrar o modificar la petición (autenticación, autorización, etc.).

**Migración:** Archivo PHP que define la estructura de una tabla de base de datos, permitiendo versionar el esquema junto al código.

**MVC:** Patrón de arquitectura Model-View-Controller que separa la lógica de negocio, la presentación y el control del flujo de la aplicación.

**MySQL:** Sistema de gestión de bases de datos relacional de código abierto, ampliamente usado en aplicaciones web.

**ORM:** Object-Relational Mapper. Herramienta que traduce entre el mundo orientado a objetos (PHP) y el mundo relacional (SQL).

**PHP:** Hypertext Preprocessor. Lenguaje de programación del lado del servidor ampliamente utilizado en desarrollo web.

**Seeder:** Clase de Laravel que puebla la base de datos con datos de prueba o datos iniciales.

**SKU:** Stock Keeping Unit. Código único que identifica una variante de producto en el inventario.

**Soft Delete:** Técnica que marca un registro como eliminado en la base de datos (campo `deleted_at`) sin borrarlo físicamente, preservando la integridad referencial.

**Tailwind CSS:** Framework CSS de utilidades que permite diseñar interfaces directamente en el HTML mediante clases predefinidas.

**Transacción:** Conjunto de operaciones de base de datos que se ejecutan como una unidad atómica: o todas tienen éxito o ninguna se aplica.

**Vite:** Herramienta de compilación de assets JavaScript y CSS de nueva generación, con servidor de desarrollo con recarga en caliente.

**WebP:** Formato de imagen moderno desarrollado por Google que proporciona compresión superior a JPEG y PNG con calidad visual comparable.

---

# 11. ANEXOS

## ANEXO A: DIAGRAMA ENTIDAD-RELACIÓN

```
┌──────────────┐        ┌─────────────────┐
│    USERS     │        │    CATEGORIAS   │
│──────────────│        │─────────────────│
│ id (PK)      │        │ id_categoria PK │
│ name         │        │ nombre          │
│ email        │        │ imagen          │
│ password     │        │ deleted_at      │
│ rol          │        └────────┬────────┘
└──────┬───────┘                 │ 1
       │ 1                       │
       │                         │ N
       │ N              ┌────────▼────────┐     ┌────────────────────────┐
┌──────▼───────┐        │    PRODUCTOS    │ 1   │ GRUPOS_OPCION_PRODUCTO │
│   PEDIDOS    │        │─────────────────│─────│────────────────────────│
│──────────────│        │ id_producto PK  │     │ id_grupo PK            │
│ id_pedido PK │        │ nombre          │     │ id_producto FK         │
│ id_usuario FK│        │ descripcion     │     │ nombre                 │
│ fecha        │        │ id_categoria FK │     │ tipo (color/texto)     │
│ total        │        │ brand           │     │ orden                  │
│ estado       │        │ precio_base     │     └───────────┬────────────┘
│ nombre_envio │        │ destacado       │                 │ 1
│ direccion    │        │ deleted_at      │                 │
│ ...          │        └────┬──────┬─────┘                │ N
└──────┬───────┘             │      │              ┌────────▼────────────────┐
       │ 1                   │      │              │ VALORES_OPCION_PRODUCTO │
       │                     │      │              │─────────────────────────│
       │ N                   │ 1    │ 1            │ id_valor PK             │
┌──────▼───────────┐         │      │              │ id_grupo FK             │
│  DETALLE_PEDIDO  │         │      │              │ nombre                  │
│──────────────────│         │      │              │ hex_code                │
│ id_detalle PK    │         │ N    │ N            │ imagen                  │
│ id_pedido FK     │ ┌───────▼──┐ ┌─▼─────────┐   │ precio_extra            │
│ id_variante FK   │ │VARIANTES │ │IMAGEN_PROD│   └──────────┬──────────────┘
│ cantidad         │ │──────────│ │───────────│              │ N
│ precio_unitario  │ │id_var PK │ │id_imagen  │              │
└──────────────────┘ │id_prod FK│ │id_prod FK │   ┌──────────▼──────────────┐
                     │precio    │ │id_valor FK│   │   VARIANTE_VALORES      │
┌─────────────┐      │stock     │ │ruta       │   │  (tabla pivote)         │
│   CARRITO   │      │sku       │ │orden      │   │─────────────────────────│
│─────────────│      └────┬─────┘ └───────────┘   │ id PK                   │
│ id_cart PK  │           │ N                      │ id_variante FK          │
│ id_usuario  │           │                        │ id_valor FK             │
│ id_variante │───────────┘                        └─────────────────────────┘
│ cantidad    │
└─────────────┘

┌───────────────────┐
│   HERO_BANNERS    │
│───────────────────│
│ id_banner PK      │
│ imagen_desktop    │
│ imagen_mobile     │
│ enlace            │
│ titulo            │
│ activo            │
│ orden             │
└───────────────────┘
```

## ANEXO B: DIAGRAMA DE RUTAS

```
GET  /                             → HomeController@index
GET  /catalogo                     → ProductController@index
GET  /producto/{id}                → ProductController@show

─── [auth + verified] ─────────────────────────────────────
GET  /carrito                      → CartController@index
POST /carrito/add                  → CartController@add
POST /carrito/remove               → CartController@remove
POST /carrito/update               → CartController@update
GET  /checkout                     → CheckoutController (Livewire)

─── [auth] ─────────────────────────────────────────────────
GET    /profile                    → ProfileController@edit
PATCH  /profile                    → ProfileController@update
DELETE /profile                    → ProfileController@destroy

─── [auth + admin] /dashboard ──────────────────────────────
GET    /                           → DashboardController@index
       /users         (resource)   → AdminUserController
       /categories    (resource)   → AdminCategoryController
       /products      (resource)   → AdminProductController
GET    /orders                     → AdminOrderController@index
GET    /orders/{pedido}            → AdminOrderController@show
PATCH  /orders/{pedido}/status     → AdminOrderController@updateStatus
GET    /stats                      → AdminStatsController@index
PATCH  /hero-banner/{banner}       → AdminHeroBannerController@update
```

## ANEXO C: MANUAL DE INSTALACIÓN

### Requisitos previos

- Docker Desktop 4.x o superior
- Git
- Puerto 80 y 3306 disponibles en la máquina host

### Instalación con Docker

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio> synapse-laravel
cd synapse-laravel

# 2. Copiar el fichero de entorno
cp .env.example .env

# 3. Editar .env si es necesario (credenciales de BD, etc.)
# Por defecto ya está configurado para Docker

# 4. Levantar los contenedores
make up
# o: docker compose up -d

# 5. Instalar dependencias PHP y Node dentro del contenedor
docker compose exec app composer install
docker compose exec app npm install && npm run build

# 6. Generar clave de aplicación
docker compose exec app php artisan key:generate

# 7. Ejecutar migraciones y datos de ejemplo
make fresh
# o: docker compose exec app php artisan migrate:fresh --seed

# 8. Crear el enlace simbólico de storage
docker compose exec app php artisan storage:link
```

La aplicación estará disponible en `http://localhost`.

### Instalación en desarrollo local (sin Docker)

```bash
# Instalar dependencias y configurar el entorno
composer setup

# Iniciar todos los servicios de desarrollo
composer dev
```

La aplicación estará disponible en `http://localhost:8000`.

### Credenciales de administrador por defecto

Tras ejecutar el seeder, el usuario administrador por defecto es:

- **Email:** admin@synapse.com
- **Contraseña:** password

## ANEXO D: MANUAL DE USUARIO

### Para clientes

**Explorar el catálogo:**
Acceda a la sección "Catálogo" desde el menú superior. Puede filtrar por categoría usando el panel lateral, ajustar el rango de precio con los controles deslizantes, y ordenar los resultados por precio (ascendente/descendente) o por novedad.

**Añadir un producto al carrito:**
En la página de detalle del producto, seleccione las opciones deseadas (color, capacidad, etc.) y pulse el botón "Añadir al carrito". Debe estar registrado e identificado para realizar esta acción.

**Gestionar el carrito:**
Desde el icono del carrito en la barra superior puede ver todos los artículos añadidos, incrementar o decrementar las cantidades usando los botones "+" y "-", y eliminar artículos. Los cambios se aplican inmediatamente sin recargar la página.

**Realizar un pedido:**
Desde el carrito, pulse "Proceder al pago". Complete los tres pasos:
1. Introduzca sus datos de envío.
2. Revise el resumen del pedido.
3. Confirme la compra.

Recibirá una referencia de pedido con el formato SYN-XXXXXX.

### Para administradores

**Acceso al panel:**
Acceda a `/dashboard` con su cuenta de administrador.

**Gestión de productos:**
En "Productos" del menú lateral puede crear nuevos productos definiendo primero los grupos de opciones (p.ej., "Color") y sus valores (p.ej., "Negro", "Blanco"), y luego las variantes con su precio y stock para cada combinación. Puede subir imágenes globales del producto e imágenes específicas por color.

**Gestión de pedidos:**
En "Pedidos" puede filtrar por estado (Pendiente, Enviado, etc.), buscar por nombre o correo del cliente, y ver el detalle de cada pedido. Para actualizar el estado, acceda al detalle del pedido y seleccione el nuevo estado en el desplegable.

**Actualizar el banner de inicio:**
En el Dashboard, en la sección "Hero Banner", puede actualizar las imágenes del banner principal para escritorio y móvil. Las imágenes se convierten automáticamente a formato WebP.
```
