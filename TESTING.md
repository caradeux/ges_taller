# Plan de Pruebas — GesTaller v2.0

## Requisitos previos

1. **MySQL corriendo** (XAMPP)
2. **PHP 8.3**: `C:\xampp\php-8.3.30-nts-Win32-vs16-x64\php.exe`
3. Ejecutar desde la raiz del proyecto:

```bash
# Instalar dependencias (si no se ha hecho)
php composer.phar install

# Migrar y sembrar datos de prueba
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve --host=127.0.0.1 --port=8000
```

> Reemplazar `php` por la ruta completa a PHP 8.3 si no esta en el PATH.

**Credenciales:** admin@gestaller.cl / admin123

---

## 1. Login y Autenticacion

- [ ] Abrir http://127.0.0.1:8000 → redirige a login
- [ ] Verificar que el login muestra diseño split-screen con icono de llave mecanica
- [ ] Login con credenciales incorrectas → muestra error
- [ ] Login con admin@gestaller.cl / admin123 → entra al dashboard
- [ ] Cerrar sesion → vuelve al login

## 2. Dashboard

- [ ] Muestra 6 tarjetas de stats: Clientes, Vehiculos, OTs Pendientes, Aprobadas, Esp. Repuestos, En Reparacion
- [ ] Muestra 3 KPIs financieros: Total Facturado, Monto Pendiente, Ticket Promedio
- [ ] Grafico de ingresos mensuales se renderiza sin error
- [ ] Tabla "Ultimas OT" muestra las 5 mas recientes
- [ ] Fecha en espanol (ej: "sabado 21 de marzo de 2026")
- [ ] Link "Ver todas las OT" funciona

## 3. Ordenes de Trabajo — Listado

- [ ] http://127.0.0.1:8000/work-orders → muestra las OTs de ejemplo
- [ ] Barra de stats por estado muestra contadores
- [ ] Click en un estado filtra la tabla
- [ ] Busqueda por folio funciona
- [ ] Busqueda por nombre de cliente funciona
- [ ] Busqueda por patente funciona
- [ ] Filtro por etiqueta funciona
- [ ] Boton "Limpiar" resetea filtros

## 4. Ordenes de Trabajo — Crear

- [ ] http://127.0.0.1:8000/work-orders/create
- [ ] Autocomplete de cliente funciona (escribir "Nelson")
- [ ] Boton "+" crea cliente rapido desde modal
- [ ] Autocomplete de vehiculo funciona (escribir "GFGR")
- [ ] Boton "+" crea vehiculo rapido desde modal
- [ ] Campos de expediente: fecha, N siniestro, N ingreso
- [ ] Selector de aseguradora con boton "+" rapido
- [ ] Selector de liquidador con boton "+" rapido
- [ ] **Inventario vehicular**: checklist de 10 items (rueda repuesto, grua, etc.)
- [ ] Campos: combustible (select), KM ingreso, llaves, conductor, declaracion objetos
- [ ] **Tabla de items**: agregar lineas con 3 precios (taller, autorizado, costo real)
- [ ] Checkbox "Aprobado" por item
- [ ] Recalculo en tiempo real de 3 totales + IVA
- [ ] Seleccion de etiquetas (checkboxes)
- [ ] Guardar → redirige a vista show con mensaje de exito
- [ ] **Title Case**: escribir "parachoque delantero" → se guarda como "Parachoque Delantero"
- [ ] **Patente**: escribir "ab-cd12" → se guarda como "ABCD12"

## 5. Ordenes de Trabajo — Ver Detalle (Show)

- [ ] Hero header oscuro con OT N, estado, fecha, patente
- [ ] Tags como badges de colores
- [ ] 3 cards: Cliente, Vehiculo, Seguro
- [ ] Inventario vehicular con checks verdes/rojos
- [ ] Tabla de items con triple precio
- [ ] Toggle de aprobacion (checkbox AJAX) → recalcula totales sin recargar
- [ ] Items no aprobados se muestran con opacidad reducida
- [ ] Triple totales (Taller, Autorizado, Costo Real) en tarjetas
- [ ] Rentabilidad: muestra ganancia y margen %
- [ ] Barra total final oscura con IVA
- [ ] Observaciones se muestran si existen
- [ ] Timeline/Historial muestra eventos cronologicos

## 6. Flujo de Estados

Desde la OT en estado "Ingreso":

- [ ] Click "Enviar Presupuesto" → estado cambia a "Presupuesto Enviado" + se asigna folio
- [ ] Click "Aprobar" → estado "Aprobado"
- [ ] Click "Esperando Repuestos" → estado "Esperando Repuestos"
- [ ] Click "Iniciar Reparacion" → estado "En Reparacion"
- [ ] Click "Completar" → estado "Completado"
- [ ] Click "Entregar" → estado "Entregado"
- [ ] Click "Facturar" → estado "Facturado"
- [ ] Verificar que cada transicion registra un evento en el historial
- [ ] Verificar que una OT facturada no se puede editar

## 7. PDF

- [ ] Desde una OT con folio, click "PDF OT" → descarga PDF
- [ ] PDF muestra: header empresa, OT N, fechas, datos cliente/vehiculo/seguro
- [ ] PDF muestra tabla de items aprobados con triple precio
- [ ] PDF muestra inventario vehicular con SI/NO
- [ ] PDF muestra firmas y texto legal
- [ ] Click "Factura" → descarga PDF factura limpio (solo total, sin desglose)

## 8. Repuestos y Pedidos

- [ ] En OT con items tipo "Repuesto" (C), aparece seccion "Repuestos y Pedidos"
- [ ] Click "Registrar Pedido" → abre modal
- [ ] Completar: proveedor, N pieza, descripcion, costo, fecha pedido → guardar
- [ ] El pedido aparece en la tabla con estado "Pedido" (badge amarillo)
- [ ] Click "Recibido" → marca como recibido con fecha actual
- [ ] El estado cambia a "Recibido" (badge verde)
- [ ] Se registra evento "Repuestos Recibidos" en el historial

## 9. Etiquetas

- [ ] http://127.0.0.1:8000/tags → lista etiquetas de ejemplo
- [ ] Crear nueva etiqueta con nombre y color
- [ ] Editar etiqueta existente
- [ ] Eliminar etiqueta
- [ ] Las etiquetas aparecen como opciones al crear/editar OT

## 10. Feriados

- [ ] http://127.0.0.1:8000/holidays → pagina de feriados
- [ ] Click "Cargar Feriados 2026" → carga 14 feriados legales de Chile
- [ ] Agregar feriado manual con fecha y nombre
- [ ] Eliminar feriado
- [ ] Selector de ano funciona

## 11. Reportes

- [ ] http://127.0.0.1:8000/reportes → reporte general con KPIs, pipeline, aseguradoras, clientes
- [ ] Filtro de fechas funciona
- [ ] Graficos Chart.js se renderizan
- [ ] http://127.0.0.1:8000/reportes/aseguradoras → reporte por aseguradora con donut chart
- [ ] http://127.0.0.1:8000/reportes/rentabilidad → reporte de rentabilidad con barras
- [ ] http://127.0.0.1:8000/reportes/repuestos → reporte de dias de espera por proveedor

## 12. Clientes

- [ ] http://127.0.0.1:8000/clients → listado con RUT monospace
- [ ] Crear cliente → nombre se guarda en Title Case
- [ ] Ver ficha de cliente → historial de OTs con status-badge
- [ ] Editar cliente
- [ ] Eliminar cliente (solo admin)

## 13. Vehiculos

- [ ] http://127.0.0.1:8000/vehicles → listado con plate-badge
- [ ] Crear vehiculo → patente se limpia automaticamente
- [ ] Ver ficha de vehiculo → datos en 2 columnas, historial de OTs
- [ ] Patente invalida (ej: "AB") → error de validacion

## 14. Administracion (admin only)

- [ ] Usuarios: crear, editar, activar/desactivar, roles
- [ ] Sucursales: crear, editar, eliminar
- [ ] Tipos de UN: CRUD inline
- [ ] Catalogo Servicios: CRUD
- [ ] Marcas/Modelos: CRUD con modelos anidados
- [ ] Roles: listar permisos

## 15. Responsive (Mobile/Tablet)

Redimensionar navegador a < 992px o usar DevTools mobile:

- [ ] Aparece barra superior con hamburguesa + logo + avatar
- [ ] Click hamburguesa → sidebar se desliza desde la izquierda con overlay
- [ ] Click overlay → sidebar se cierra
- [ ] Click en un link del menu → sidebar se cierra y navega
- [ ] Dashboard: stats se apilan en 3 columnas (tablet) o 2 (mobile)
- [ ] Tablas son scrolleables horizontalmente
- [ ] Formularios ocupan ancho completo
- [ ] OT show: hero compacto, totales en 1 columna en mobile
- [ ] Filtros se apilan verticalmente en mobile

## 16. Permisos por Rol

- [ ] Crear usuario con rol "recepcion" → puede crear OTs pero no eliminar
- [ ] Crear usuario con rol "taller" → solo lectura de OTs
- [ ] Verificar que "taller" no ve menu de administracion
- [ ] Verificar que "recepcion" no ve menu de administracion

## 17. Multi-Sucursal

- [ ] Admin: selector de sucursal en sidebar cambia los datos visibles
- [ ] "Todas las sucursales" muestra todo
- [ ] Seleccionar una sucursal filtra OTs, clientes, vehiculos

---

## Notas

- La BD se puede resetear en cualquier momento con: `php artisan migrate:fresh --seed`
- Credenciales de admin: admin@gestaller.cl / admin123
- Los emails de notificacion requieren configuracion SMTP en .env (MAIL_MAILER, MAIL_HOST, etc.)
