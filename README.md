# GesTaller - Sistema de Gestion de Taller Automotriz

Sistema integral para la gestion de ordenes de trabajo, repuestos, seguros y reportes de rentabilidad para talleres de reparacion automotriz. Localizado para Chile (CLP, RUT, interfaz en espanol).

## Stack Tecnologico

- **Backend:** Laravel 12 (PHP 8.2+)
- **Base de datos:** MySQL 8
- **Frontend:** Blade + Bootstrap 5.3 + Bootstrap Icons + Chart.js
- **PDF:** barryvdh/laravel-dompdf
- **Assets:** Servidos localmente (sin CDN)

## Funcionalidades Principales

### Ordenes de Trabajo (OT)
- Creacion directa de OT sin necesidad de cotizacion previa (compatible con Auto3P/Audatex)
- **Triple registro de montos** por item: Monto Taller, Monto Autorizado, Costo Real
- **Aprobacion granular** por item (checkbox) — solo items aprobados suman al total
- Asignacion atomica de folio al enviar presupuesto (DB lock para concurrencia)
- Generacion de PDF tecnico (con desglose) y PDF de factura "limpio" (solo total)

### Flujo de Estados
```
Ingreso → Presupuesto Enviado → Aprobado → Esperando Repuestos → En Reparacion → Completado → Entregado → Facturado
```

### Timeline / Trazabilidad
- Registro automatico de eventos en cada transicion de estado
- Historial cronologico visible en el detalle de cada OT
- Eventos: ingreso, envio de presupuesto, aprobacion, repuestos pedidos/recibidos, reparacion, entrega

### Logistica de Repuestos
- Pedidos de repuestos vinculados a items de tipo "Repuesto"
- Tracking: proveedor, numero de pieza, fecha pedido, fecha recepcion, costo
- Indicadores visuales: Pendiente / Pedido / Recibido
- Reporte de dias de espera por proveedor

### Etiquetas (Tags)
- Sistema de etiquetas con colores para clasificar OTs
- Ejemplos: "Urgente", "Pendiente de Repuesto", "Re-inspeccion"
- Filtro por etiqueta en listados

### Multi-Sucursal
- Soporte para multiples sucursales
- Admin puede ver todas las sucursales o filtrar por una
- Otros roles ven solo su sucursal asignada
- Selector de sucursal en el sidebar

### Roles y Permisos
| Rol | Acceso |
|-----|--------|
| **admin** | Acceso completo, gestion de usuarios/roles/sucursales/catalogos |
| **recepcion** | OTs (crear, editar, seguimiento), clientes, vehiculos, reportes |
| **taller** | Solo lectura de OTs, clientes y vehiculos |

Permisos configurados en `config/permissions.php` con soporte para wildcards.

### Reportes
- **Dashboard:** KPIs (clientes, OTs pendientes/aprobadas, en reparacion, facturado), grafico de ingresos mensuales
- **Reporte General:** Resumen ejecutivo, pipeline por estado, ingresos por aseguradora, top clientes, repuestos vs mano de obra
- **Reporte por Aseguradora:** Filtro por fecha, montos autorizados y facturados por compania
- **Reporte de Rentabilidad:** Ganancia real por OT (autorizado - costo real), margen porcentual
- **Reporte de Repuestos:** Dias promedio de espera por proveedor

### Gestion de Seguros
- Companias de seguros y liquidadores
- Numero de siniestro y numero de ingreso por OT
- Deducible configurable

### Catalogos
- **Tipos de UN:** Clasificacion de items (Reparacion, Pintura, D/M, Repuesto, Otros)
- **Catalogo de Servicios:** Items reutilizables con precios por defecto
- **Marcas y Modelos:** Catalogo de marcas de vehiculos con modelos anidados

## Modelo de Datos

```
WorkOrder (work_orders)
  ├── belongsTo: Branch, Client, Vehicle, InsuranceCompany?, Liquidator?
  ├── hasMany: WorkOrderItem, WorkOrderEvent, PartOrder (through items)
  └── morphToMany: Tag

WorkOrderItem (work_order_items)
  ├── belongsTo: WorkOrder, UnType
  ├── hasMany: PartOrder
  └── Campos: price_workshop, price_authorized, price_real, is_approved, is_salvage

WorkOrderEvent (work_order_events)
  ├── belongsTo: WorkOrder, User
  └── Campos: event_type, description, occurred_at, metadata

PartOrder (part_orders)
  ├── belongsTo: WorkOrderItem
  └── Campos: supplier, part_number, cost, ordered_at, received_at

Tag (tags) ←→ WorkOrder (taggables pivot)

Client → hasMany: Vehicle, WorkOrder
Vehicle → hasMany: WorkOrder
InsuranceCompany → hasMany: Liquidator
Branch → hasMany: User, Client, Vehicle, WorkOrder
Company (config singleton): folio_counter, ot_folio_counter, quotation_validity_days
```

## Instalacion

### Requisitos
- PHP 8.2+
- MySQL 8
- Composer
- Node.js + npm

### Setup rapido
```bash
git clone <repo-url> ges_taller
cd ges_taller
composer install
cp .env.example .env
php artisan key:generate
```

### Configurar base de datos
Editar `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ges_taller
DB_USERNAME=root
DB_PASSWORD=
```

### Migrar y sembrar datos
```bash
php artisan migrate
php artisan db:seed
```

El seeder crea:
- Usuario admin: `admin@gestaller.cl` / `admin123`
- 2 clientes, 2 vehiculos, 2 OTs de ejemplo
- Companias de seguros, liquidadores, etiquetas de ejemplo
- Tipos de UN (REP, PINT, D/M, C, MAT)

### Desarrollo
```bash
npm install && npm run build
php artisan serve
```

Abrir http://localhost:8000

## Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── WorkOrderController.php    # CRUD OT, estados, folio, PDFs, toggle aprobacion
│   ├── PartOrderController.php    # CRUD pedidos de repuestos
│   ├── TagController.php          # CRUD etiquetas
│   ├── DashboardController.php    # Panel general con KPIs
│   ├── ReportController.php       # Reportes generales, aseguradoras, rentabilidad, repuestos
│   ├── ClientController.php       # CRUD clientes + autocomplete + creacion rapida
│   ├── VehicleController.php      # CRUD vehiculos + autocomplete + creacion rapida
│   └── ...
├── Models/
│   ├── WorkOrder.php              # OT con accessors de estado, folio, relaciones
│   ├── WorkOrderItem.php          # Items con triple precio + aprobacion
│   ├── WorkOrderEvent.php         # Timeline con labels, iconos y colores
│   ├── PartOrder.php              # Repuestos con lead time calculado
│   ├── Tag.php                    # Etiquetas con slug automatico
│   └── ...
├── Services/
│   └── WorkOrderTimelineService.php  # Registro automatico de eventos
│
resources/views/
├── work_orders/                   # index, create, edit, show, followup, pdf, invoice_pdf
├── tags/                          # index (CRUD inline con modales)
├── reports/                       # index, pdf, insurance, profitability, parts
├── layouts/app.blade.php          # Layout principal con sidebar
├── dashboard.blade.php            # Panel con Chart.js
└── auth/login.blade.php           # Login split-screen
```

## API Endpoints (internos, JSON)

| Endpoint | Descripcion |
|----------|-------------|
| `GET /api/clients/search?q=` | Autocomplete clientes (nombre/RUT) |
| `GET /api/vehicles/search?q=&client_id=` | Autocomplete vehiculos (patente/marca) |
| `GET /api/un-types` | Lista de tipos UN activos |
| `GET /api/service-items/search?q=` | Autocomplete servicios |
| `GET /api/tags/search?q=` | Autocomplete etiquetas |
| `GET /api/vehicle-brands` | Lista de marcas |
| `GET /api/vehicle-brands/{id}/models` | Modelos de una marca |
| `POST /work-orders/{id}/items/{item}/toggle-approval` | Toggle aprobacion (AJAX) |

## Localizacion

- Interfaz completamente en espanol
- Moneda: CLP (peso chileno)
- Formato de fecha: `d/m/Y`
- Identificador: RUT chileno
- Patente: formato alfanumerico sin guion (ej: `ABCD12`)
