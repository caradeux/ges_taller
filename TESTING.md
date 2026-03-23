# Plan de Pruebas — GesTaller v2.0 (Completo)

## Requisitos previos

1. **MySQL corriendo** (XAMPP)
2. **PHP 8.3**: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`
3. Ejecutar desde la raiz del proyecto:

```bash
# Migrar y sembrar datos de prueba
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve --host=127.0.0.1 --port=8000
```

**Credenciales:** admin@gestaller.cl / admin123

---

## 1. Login y Autenticacion

### 1.1 Acceso sin sesion
- [ ] **TC-1.1.1** Abrir http://127.0.0.1:8000 sin sesion → redirige a /login
- [ ] **TC-1.1.2** Intentar acceder a /work-orders sin sesion → redirige a /login
- [ ] **TC-1.1.3** Intentar acceder a /clients sin sesion → redirige a /login

### 1.2 Interfaz de login
- [ ] **TC-1.2.1** Pagina de login muestra diseño split-screen con icono de llave mecanica
- [ ] **TC-1.2.2** Formulario tiene campos: Email, Contraseña
- [ ] **TC-1.2.3** Checkbox "Recordarme" presente

### 1.3 Validacion de credenciales
- [ ] **TC-1.3.1** Login con email vacio → error de validacion "El campo email es obligatorio"
- [ ] **TC-1.3.2** Login con contraseña vacia → error de validacion
- [ ] **TC-1.3.3** Login con email inexistente → error "Credenciales incorrectas"
- [ ] **TC-1.3.4** Login con contraseña incorrecta → error "Credenciales incorrectas"
- [ ] **TC-1.3.5** Login con admin@gestaller.cl / admin123 → redirige al dashboard

### 1.4 Cierre de sesion
- [ ] **TC-1.4.1** Click en "Cerrar sesion" → redirige a /login
- [ ] **TC-1.4.2** Tras cerrar sesion, acceder a /work-orders → redirige a /login

---

## 2. Dashboard

### 2.1 Tarjetas de estadisticas
- [ ] **TC-2.1.1** Muestra tarjeta "Clientes" con contador correcto
- [ ] **TC-2.1.2** Muestra tarjeta "Vehiculos" con contador correcto
- [ ] **TC-2.1.3** Muestra tarjeta "OTs Pendientes" (status intake + budget_sent)
- [ ] **TC-2.1.4** Muestra tarjeta "Aprobadas" (status approved)
- [ ] **TC-2.1.5** Muestra tarjeta "Esp. Repuestos" (status waiting_parts)
- [ ] **TC-2.1.6** Muestra tarjeta "En Reparacion" (status in_repair)

### 2.2 KPIs financieros
- [ ] **TC-2.2.1** Total Facturado: suma de total_authorized de OTs con status 'invoiced'
- [ ] **TC-2.2.2** Monto Pendiente: suma de OTs no facturadas
- [ ] **TC-2.2.3** Ticket Promedio: promedio de montos facturados

### 2.3 Graficos y tablas
- [ ] **TC-2.3.1** Grafico de ingresos mensuales (Chart.js) se renderiza sin error
- [ ] **TC-2.3.2** Grafico muestra ultimos 6 meses
- [ ] **TC-2.3.3** Tabla "Ultimas OT" muestra las 5 mas recientes
- [ ] **TC-2.3.4** Cada OT en la tabla tiene: folio, cliente, patente, estado con badge de color
- [ ] **TC-2.3.5** Link "Ver todas las OT" navega a /work-orders

### 2.4 Formato y localizacion
- [ ] **TC-2.4.1** Fecha en español (ej: "sabado 22 de marzo de 2026")
- [ ] **TC-2.4.2** Montos en formato CLP ($ con separador de miles)

---

## 3. Ordenes de Trabajo — Listado

### 3.1 Vista general
- [ ] **TC-3.1.1** GET /work-orders muestra listado con OTs de ejemplo
- [ ] **TC-3.1.2** Tabla muestra columnas: Folio, Cliente, Patente, Fecha, Estado, Acciones
- [ ] **TC-3.1.3** Paginacion de 10 registros por pagina
- [ ] **TC-3.1.4** OTs sin folio muestran "Sin Folio" o "Borrador"

### 3.2 Barra de estados
- [ ] **TC-3.2.1** Barra de stats muestra contadores por cada estado
- [ ] **TC-3.2.2** Click en un estado filtra la tabla por ese estado
- [ ] **TC-3.2.3** El estado seleccionado se resalta visualmente

### 3.3 Busqueda y filtros
- [ ] **TC-3.3.1** Busqueda por folio funciona (ej: "0001")
- [ ] **TC-3.3.2** Busqueda por nombre de cliente funciona (ej: "Nelson")
- [ ] **TC-3.3.3** Busqueda por RUT de cliente funciona
- [ ] **TC-3.3.4** Busqueda por patente funciona (ej: "GFGR")
- [ ] **TC-3.3.5** Busqueda por nombre de aseguradora funciona
- [ ] **TC-3.3.6** Filtro por etiqueta funciona (seleccionar tag)
- [ ] **TC-3.3.7** Boton "Limpiar" resetea todos los filtros
- [ ] **TC-3.3.8** Busqueda sin resultados muestra mensaje "No se encontraron resultados"

---

## 4. Ordenes de Trabajo — Crear

### 4.1 Formulario basico
- [ ] **TC-4.1.1** GET /work-orders/create carga formulario completo
- [ ] **TC-4.1.2** Campo fecha pre-llenado con fecha actual
- [ ] **TC-4.1.3** Status inicial es "Ingreso" (no editable)

### 4.2 Seleccion de cliente (autocomplete)
- [ ] **TC-4.2.1** Escribir nombre parcial de cliente → muestra sugerencias dropdown
- [ ] **TC-4.2.2** Seleccionar cliente del dropdown → llena campo oculto client_id
- [ ] **TC-4.2.3** Escribir RUT parcial → muestra sugerencias
- [ ] **TC-4.2.4** Busqueda sin resultados → no muestra dropdown
- [ ] **TC-4.2.5** Boton "+" abre modal de creacion rapida de cliente
- [ ] **TC-4.2.6** Modal: completar nombre + RUT → crea cliente y lo selecciona automaticamente

### 4.3 Seleccion de vehiculo (autocomplete)
- [ ] **TC-4.3.1** Escribir patente parcial → muestra sugerencias
- [ ] **TC-4.3.2** Seleccionar vehiculo → llena campo oculto vehicle_id
- [ ] **TC-4.3.3** Boton "+" abre modal de creacion rapida de vehiculo
- [ ] **TC-4.3.4** Modal: completar patente, marca, modelo → crea vehiculo
- [ ] **TC-4.3.5** Patente ingresada en minusculas se guarda en MAYUSCULAS sin guiones

### 4.4 Datos de expediente/seguro
- [ ] **TC-4.4.1** Campo fecha de ingreso funciona
- [ ] **TC-4.4.2** Campo N° siniestro es opcional
- [ ] **TC-4.4.3** Campo N° ingreso es opcional
- [ ] **TC-4.4.4** Selector de aseguradora muestra lista de aseguradoras existentes
- [ ] **TC-4.4.5** Boton "+" crea aseguradora rapida (nombre unico)
- [ ] **TC-4.4.6** Selector de liquidador muestra liquidadores de la aseguradora seleccionada
- [ ] **TC-4.4.7** Boton "+" crea liquidador rapido
- [ ] **TC-4.4.8** Campo deducible es numerico >= 0

### 4.5 Inventario vehicular
- [ ] **TC-4.5.1** Checklist de items: rueda repuesto, grua, gata, kit seguridad, panel radio, alfombras, antena, logos, TAG, objetos de valor
- [ ] **TC-4.5.2** Selector de combustible (1/4, 1/2, 3/4, Lleno)
- [ ] **TC-4.5.3** Campo KM ingreso numerico >= 0
- [ ] **TC-4.5.4** Campo cantidad de llaves (entero >= 0)
- [ ] **TC-4.5.5** Campo nombre del conductor (texto)
- [ ] **TC-4.5.6** Campo declaracion de objetos (texto largo)

### 4.6 Tabla de items (lineas de trabajo)
- [ ] **TC-4.6.1** Al menos 1 item es requerido para guardar
- [ ] **TC-4.6.2** Cada item tiene: tipo UN, descripcion, precio taller, precio autorizado, precio costo real
- [ ] **TC-4.6.3** Selector de tipo UN muestra tipos activos (repair, paint, dm, parts, other, service)
- [ ] **TC-4.6.4** Checkbox "Aprobado" por item funciona
- [ ] **TC-4.6.5** Checkbox "Salvamento" por item funciona
- [ ] **TC-4.6.6** Boton agregar linea añade nueva fila
- [ ] **TC-4.6.7** Boton eliminar linea remueve la fila
- [ ] **TC-4.6.8** Recalculo en tiempo real de 3 totales (taller, autorizado, costo real)
- [ ] **TC-4.6.9** IVA se calcula como 19% del total autorizado
- [ ] **TC-4.6.10** Total autorizado solo suma items marcados como aprobados
- [ ] **TC-4.6.11** Descripcion "parachoque delantero" se guarda como "Parachoque Delantero" (Title Case)

### 4.7 Etiquetas
- [ ] **TC-4.7.1** Checkboxes de etiquetas disponibles se muestran
- [ ] **TC-4.7.2** Se pueden seleccionar multiples etiquetas
- [ ] **TC-4.7.3** Las etiquetas se guardan correctamente con la OT

### 4.8 Guardar OT
- [ ] **TC-4.8.1** Guardar con datos validos → redirige a vista show con mensaje de exito
- [ ] **TC-4.8.2** Guardar sin cliente → error de validacion
- [ ] **TC-4.8.3** Guardar sin vehiculo → error de validacion
- [ ] **TC-4.8.4** Guardar sin items → error de validacion
- [ ] **TC-4.8.5** Se registra evento "Ingreso" en el historial
- [ ] **TC-4.8.6** OT se crea con status "intake" y sin folio

### 4.9 Reglas de negocio Title Case y Patente
- [ ] **TC-4.9.1** Nombre de cliente "juan perez" se guarda como "Juan Perez"
- [ ] **TC-4.9.2** Patente "ab-cd12" se guarda como "ABCD12"
- [ ] **TC-4.9.3** Patente "AB CD 12" se guarda como "ABCD12"
- [ ] **TC-4.9.4** Patente invalida "AB" (menos de 4 chars) → error de validacion

---

## 5. Ordenes de Trabajo — Ver Detalle (Show)

### 5.1 Header y datos generales
- [ ] **TC-5.1.1** Hero header oscuro muestra: OT N°, estado con badge de color, fecha, patente
- [ ] **TC-5.1.2** Tags como badges de colores debajo del header
- [ ] **TC-5.1.3** OT sin folio muestra "Sin Folio"

### 5.2 Tarjetas de informacion
- [ ] **TC-5.2.1** Card "Cliente" muestra nombre, RUT, telefono, email
- [ ] **TC-5.2.2** Card "Vehiculo" muestra patente, marca/modelo, año, color, VIN, kilometraje
- [ ] **TC-5.2.3** Card "Seguro" muestra aseguradora, liquidador, N° siniestro, deducible
- [ ] **TC-5.2.4** Card "Seguro" no se muestra si no tiene aseguradora

### 5.3 Inventario vehicular
- [ ] **TC-5.3.1** Muestra checklist con iconos verdes (tiene) y rojos (no tiene)
- [ ] **TC-5.3.2** Muestra combustible, KM, llaves, conductor
- [ ] **TC-5.3.3** Muestra declaracion de objetos si existe

### 5.4 Tabla de items
- [ ] **TC-5.4.1** Muestra todos los items con tipo, descripcion, 3 precios
- [ ] **TC-5.4.2** Toggle de aprobacion (checkbox AJAX) funciona sin recargar pagina
- [ ] **TC-5.4.3** Al aprobar/desaprobar un item, los totales se recalculan en tiempo real
- [ ] **TC-5.4.4** Items no aprobados se muestran con opacidad reducida
- [ ] **TC-5.4.5** Items marcados como salvamento se identifican visualmente

### 5.5 Totales y rentabilidad
- [ ] **TC-5.5.1** Tres tarjetas de totales: Taller, Autorizado, Costo Real
- [ ] **TC-5.5.2** Total Autorizado solo suma items aprobados
- [ ] **TC-5.5.3** Rentabilidad: ganancia = autorizado - costo real
- [ ] **TC-5.5.4** Rentabilidad: margen % = (ganancia / autorizado) * 100
- [ ] **TC-5.5.5** Barra total final oscura con subtotal + IVA 19% + total con IVA

### 5.6 Observaciones y timeline
- [ ] **TC-5.6.1** Observaciones se muestran si existen
- [ ] **TC-5.6.2** Timeline/Historial muestra eventos cronologicos
- [ ] **TC-5.6.3** Cada evento muestra: icono, tipo, descripcion, fecha/hora, usuario

### 5.7 Historial del vehiculo
- [ ] **TC-5.7.1** Seccion de historial muestra OTs anteriores del mismo vehiculo
- [ ] **TC-5.7.2** No muestra la OT actual en el historial

### 5.8 Botones de accion
- [ ] **TC-5.8.1** Boton "Editar" presente (si no esta facturada)
- [ ] **TC-5.8.2** Boton "PDF OT" presente (solo si tiene folio)
- [ ] **TC-5.8.3** Boton "Factura PDF" presente (solo si tiene folio)
- [ ] **TC-5.8.4** Boton "PDF Ingreso" siempre presente
- [ ] **TC-5.8.5** Botones de transicion de estado visibles segun estado actual

---

## 6. Flujo de Estados (Ciclo de Vida OT)

### 6.1 Transiciones de estado
- [ ] **TC-6.1.1** Ingreso → "Enviar Presupuesto" → estado "Presupuesto Enviado" + se asigna folio
- [ ] **TC-6.1.2** El folio se asigna con formato 4 digitos (ej: "0011")
- [ ] **TC-6.1.3** Presupuesto Enviado → "Aprobar" → estado "Aprobado"
- [ ] **TC-6.1.4** Aprobado → "Esperando Repuestos" → estado "Esperando Repuestos"
- [ ] **TC-6.1.5** Aprobado → "Iniciar Reparacion" → estado "En Reparacion"
- [ ] **TC-6.1.6** Esperando Repuestos → "Iniciar Reparacion" → estado "En Reparacion"
- [ ] **TC-6.1.7** En Reparacion → "Completar" → estado "Completado"
- [ ] **TC-6.1.8** Completado → "Entregar" → estado "Entregado"
- [ ] **TC-6.1.9** Entregado → "Facturar" → estado "Facturado"

### 6.2 Reglas de negocio del folio
- [ ] **TC-6.2.1** OT en estado "Ingreso" no tiene folio
- [ ] **TC-6.2.2** Al enviar presupuesto, folio se asigna atomicamente (sin duplicados)
- [ ] **TC-6.2.3** El folio_counter de la empresa se incrementa tras la asignacion
- [ ] **TC-6.2.4** No se puede descargar PDF si la OT no tiene folio

### 6.3 Historial de eventos
- [ ] **TC-6.3.1** Cada transicion de estado registra un evento en el historial
- [ ] **TC-6.3.2** El evento incluye: tipo, descripcion, usuario, fecha/hora
- [ ] **TC-6.3.3** Metadata del evento incluye old_status y new_status

### 6.4 Restricciones
- [ ] **TC-6.4.1** OT facturada no se puede editar (boton Editar deshabilitado/oculto)
- [ ] **TC-6.4.2** OT facturada puede tener numero de factura

### 6.5 Notificaciones WhatsApp
- [ ] **TC-6.5.1** Al cambiar estado, si el cliente tiene telefono, se genera enlace WhatsApp
- [ ] **TC-6.5.2** Mensaje WhatsApp incluye saludo segun hora (Buenos dias/tardes/noches)
- [ ] **TC-6.5.3** Mensaje incluye datos del vehiculo (patente, marca, modelo)

---

## 7. PDF

### 7.1 PDF de OT (Presupuesto)
- [ ] **TC-7.1.1** Desde OT con folio, click "PDF OT" → descarga PDF
- [ ] **TC-7.1.2** PDF muestra: header empresa (nombre, RUT, direccion, logo)
- [ ] **TC-7.1.3** PDF muestra: OT N° (folio), fechas
- [ ] **TC-7.1.4** PDF muestra: datos cliente (nombre, RUT)
- [ ] **TC-7.1.5** PDF muestra: datos vehiculo (patente, marca, modelo, año, color)
- [ ] **TC-7.1.6** PDF muestra: datos seguro si aplica
- [ ] **TC-7.1.7** PDF muestra: tabla de items aprobados con triple precio
- [ ] **TC-7.1.8** PDF muestra: inventario vehicular con SI/NO
- [ ] **TC-7.1.9** PDF muestra: totales, IVA, total con IVA
- [ ] **TC-7.1.10** PDF muestra: firmas y texto legal

### 7.2 PDF de Factura
- [ ] **TC-7.2.1** Click "Factura" → descarga PDF factura
- [ ] **TC-7.2.2** PDF factura es limpio (puede no tener desglose completo)
- [ ] **TC-7.2.3** Bloqueado si OT no tiene folio

### 7.3 PDF de Ingreso
- [ ] **TC-7.3.1** Click "PDF Ingreso" → descarga PDF de acta de ingreso
- [ ] **TC-7.3.2** No requiere folio para descargar
- [ ] **TC-7.3.3** Muestra inventario vehicular completo

---

## 8. Repuestos y Pedidos (Part Orders)

### 8.1 Visualizacion
- [ ] **TC-8.1.1** En OT con items tipo "Repuesto" (C/parts), aparece seccion "Repuestos y Pedidos"
- [ ] **TC-8.1.2** Lista de pedidos muestra: proveedor, N° pieza, descripcion, costo, fecha pedido, estado

### 8.2 Crear pedido
- [ ] **TC-8.2.1** Click "Registrar Pedido" → abre modal de creacion
- [ ] **TC-8.2.2** Campo item de OT (seleccion del item asociado)
- [ ] **TC-8.2.3** Campo proveedor (texto)
- [ ] **TC-8.2.4** Campo N° pieza (texto)
- [ ] **TC-8.2.5** Campo descripcion (requerido)
- [ ] **TC-8.2.6** Campo costo (numerico >= 0)
- [ ] **TC-8.2.7** Campo fecha pedido (date)
- [ ] **TC-8.2.8** Guardar → pedido aparece en tabla con estado "Pendiente" o "Pedido"

### 8.3 Estados del pedido
- [ ] **TC-8.3.1** Sin fecha de pedido → estado "Pendiente" (badge gris)
- [ ] **TC-8.3.2** Con fecha de pedido → estado "Pedido" (badge amarillo)
- [ ] **TC-8.3.3** Click "Recibido" → estado "Recibido" (badge verde) con fecha actual
- [ ] **TC-8.3.4** Se registra evento "Repuestos Recibidos" en el historial de la OT

### 8.4 Edicion y eliminacion
- [ ] **TC-8.4.1** Editar pedido: actualizar proveedor, costo, notas
- [ ] **TC-8.4.2** Eliminar pedido: confirmar y eliminar

### 8.5 Tiempo de entrega (Lead Time)
- [ ] **TC-8.5.1** Lead time se calcula: dias entre fecha pedido y fecha recibido
- [ ] **TC-8.5.2** Si no tiene ambas fechas, lead time es null

---

## 9. Etiquetas (Tags)

### 9.1 Listado
- [ ] **TC-9.1.1** GET /tags muestra lista de etiquetas existentes
- [ ] **TC-9.1.2** Cada etiqueta muestra nombre y color

### 9.2 Crear etiqueta
- [ ] **TC-9.2.1** Formulario: nombre (requerido, unico, max 50) + color
- [ ] **TC-9.2.2** Guardar → etiqueta aparece en lista
- [ ] **TC-9.2.3** Nombre duplicado → error de validacion

### 9.3 Editar etiqueta
- [ ] **TC-9.3.1** Click editar → campos editables
- [ ] **TC-9.3.2** Guardar cambios → se actualiza

### 9.4 Eliminar etiqueta
- [ ] **TC-9.4.1** Click eliminar → confirmacion
- [ ] **TC-9.4.2** Confirmar → etiqueta eliminada

### 9.5 Integracion con OTs
- [ ] **TC-9.5.1** Las etiquetas aparecen como opciones al crear/editar OT
- [ ] **TC-9.5.2** Etiquetas asignadas se muestran como badges en la vista show

---

## 10. Feriados (Holidays)

### 10.1 Listado
- [ ] **TC-10.1.1** GET /holidays muestra pagina de feriados
- [ ] **TC-10.1.2** Selector de año funciona (cambia los feriados mostrados)
- [ ] **TC-10.1.3** Lista muestra: fecha, nombre del feriado

### 10.2 Cargar feriados predeterminados
- [ ] **TC-10.2.1** Click "Cargar Feriados [año]" → carga 14 feriados legales de Chile
- [ ] **TC-10.2.2** Si ya existen feriados, no duplica los existentes
- [ ] **TC-10.2.3** Mensaje indica cuantos feriados se cargaron

### 10.3 Agregar feriado manual
- [ ] **TC-10.3.1** Formulario: fecha (requerida, unica) + nombre (requerido)
- [ ] **TC-10.3.2** Fecha duplicada → error de validacion
- [ ] **TC-10.3.3** Guardar → feriado aparece en lista

### 10.4 Eliminar feriado
- [ ] **TC-10.4.1** Click eliminar → feriado eliminado
- [ ] **TC-10.4.2** Se recalculan SLAs si aplica

---

## 11. Reportes

### 11.1 Reporte general
- [ ] **TC-11.1.1** GET /reportes carga sin error
- [ ] **TC-11.1.2** KPIs ejecutivos: total facturado, OTs facturadas, ticket promedio, tasa aprobacion
- [ ] **TC-11.1.3** Pipeline: contadores y montos por estado
- [ ] **TC-11.1.4** Top aseguradoras por ingresos
- [ ] **TC-11.1.5** Top 10 clientes por monto
- [ ] **TC-11.1.6** Desglose por tipo de item (repuestos vs mano de obra)
- [ ] **TC-11.1.7** Grafico de tendencia mensual

### 11.2 Filtros de reporte
- [ ] **TC-11.2.1** Filtro de fecha "desde" funciona
- [ ] **TC-11.2.2** Filtro de fecha "hasta" funciona
- [ ] **TC-11.2.3** Por defecto muestra datos del año en curso (YTD)
- [ ] **TC-11.2.4** Graficos Chart.js se renderizan correctamente

### 11.3 Reporte por aseguradora
- [ ] **TC-11.3.1** GET /reportes/aseguradoras carga sin error
- [ ] **TC-11.3.2** Grafico donut muestra distribucion por aseguradora
- [ ] **TC-11.3.3** Tabla: aseguradora, cantidad OTs, total autorizado, total facturado

### 11.4 Reporte de rentabilidad
- [ ] **TC-11.4.1** GET /reportes/rentabilidad carga sin error
- [ ] **TC-11.4.2** Grafico de barras muestra rentabilidad
- [ ] **TC-11.4.3** Solo OTs aprobadas o superior
- [ ] **TC-11.4.4** Calculo: ganancia = autorizado - costo real, margen % = (ganancia/autorizado)*100

### 11.5 Reporte de estado taller
- [ ] **TC-11.5.1** GET /reportes/estado-taller carga sin error
- [ ] **TC-11.5.2** OTs activas agrupadas por estado
- [ ] **TC-11.5.3** Contadores y totales por estado

### 11.6 Reporte de facturacion
- [ ] **TC-11.6.1** GET /reportes/facturacion carga sin error
- [ ] **TC-11.6.2** Lista OTs con filtro de estado
- [ ] **TC-11.6.3** Resumen: total, facturadas, pendientes, monto facturado

### 11.7 Reporte de repuestos
- [ ] **TC-11.7.1** GET /reportes/repuestos carga sin error
- [ ] **TC-11.7.2** Agrupacion por proveedor
- [ ] **TC-11.7.3** Muestra: cantidad pedidos, promedio dias entrega, maximo dias
- [ ] **TC-11.7.4** Solo repuestos recibidos (con ambas fechas)

### 11.8 PDF de reporte
- [ ] **TC-11.8.1** GET /reportes/pdf genera PDF descargable
- [ ] **TC-11.8.2** PDF contiene los mismos datos que el reporte web

---

## 12. Clientes

### 12.1 Listado
- [ ] **TC-12.1.1** GET /clients muestra listado de clientes
- [ ] **TC-12.1.2** RUT en formato monospace
- [ ] **TC-12.1.3** Busqueda por nombre funciona
- [ ] **TC-12.1.4** Busqueda por RUT funciona
- [ ] **TC-12.1.5** Busqueda por email funciona
- [ ] **TC-12.1.6** Busqueda por telefono funciona
- [ ] **TC-12.1.7** Paginacion de 10 por pagina

### 12.2 Crear cliente
- [ ] **TC-12.2.1** GET /clients/create muestra formulario
- [ ] **TC-12.2.2** RUT/DNI requerido y unico
- [ ] **TC-12.2.3** Nombre requerido, max 255
- [ ] **TC-12.2.4** Telefono opcional, max 20
- [ ] **TC-12.2.5** Email opcional, formato valido
- [ ] **TC-12.2.6** Direccion opcional
- [ ] **TC-12.2.7** Nombre se guarda en Title Case ("juan perez" → "Juan Perez")
- [ ] **TC-12.2.8** Se asigna automaticamente a la sucursal del usuario

### 12.3 Ver ficha de cliente
- [ ] **TC-12.3.1** GET /clients/{id} muestra datos completos
- [ ] **TC-12.3.2** Lista de vehiculos del cliente
- [ ] **TC-12.3.3** Historial de OTs con status-badge de colores
- [ ] **TC-12.3.4** Link a cada OT funciona

### 12.4 Editar cliente
- [ ] **TC-12.4.1** GET /clients/{id}/edit muestra formulario con datos actuales
- [ ] **TC-12.4.2** Actualizar nombre → se guarda en Title Case
- [ ] **TC-12.4.3** RUT duplicado (de otro cliente) → error

### 12.5 Eliminar cliente
- [ ] **TC-12.5.1** Solo admin puede eliminar (recepcion no ve boton)
- [ ] **TC-12.5.2** Confirmar eliminacion → cliente eliminado
- [ ] **TC-12.5.3** Se eliminan vehiculos y OTs en cascada (si aplica)

---

## 13. Vehiculos

### 13.1 Listado
- [ ] **TC-13.1.1** GET /vehicles muestra listado con plate-badge
- [ ] **TC-13.1.2** Muestra: patente, marca, modelo, año, cliente
- [ ] **TC-13.1.3** Busqueda funciona (patente, marca, modelo)

### 13.2 Crear vehiculo
- [ ] **TC-13.2.1** GET /vehicles/create muestra formulario
- [ ] **TC-13.2.2** Patente requerida, regex ^[A-Za-z0-9]{4,6}$
- [ ] **TC-13.2.3** Patente se limpia automaticamente (sin guiones, mayusculas)
- [ ] **TC-13.2.4** Marca y modelo requeridos
- [ ] **TC-13.2.5** Año opcional, rango 1900 a año actual+1
- [ ] **TC-13.2.6** Color opcional, max 50
- [ ] **TC-13.2.7** VIN/Chasis opcional, unico
- [ ] **TC-13.2.8** Odometro opcional, entero >= 0
- [ ] **TC-13.2.9** Cliente seleccionable via autocomplete

### 13.3 Ver ficha de vehiculo
- [ ] **TC-13.3.1** GET /vehicles/{id} muestra datos en 2 columnas
- [ ] **TC-13.3.2** Historial de OTs del vehiculo
- [ ] **TC-13.3.3** Link al cliente propietario

### 13.4 Editar vehiculo
- [ ] **TC-13.4.1** GET /vehicles/{id}/edit muestra datos actuales
- [ ] **TC-13.4.2** Actualizar patente → se limpia y guarda en mayusculas

### 13.5 Validaciones de patente
- [ ] **TC-13.5.1** Patente "AB" (2 chars) → error "entre 4 y 6 caracteres"
- [ ] **TC-13.5.2** Patente "ABCDEFG" (7 chars) → error
- [ ] **TC-13.5.3** Patente "AB-12" → se limpia a "AB12" (4 chars, valida)
- [ ] **TC-13.5.4** Patente duplicada → error "ya existe"

---

## 14. Administracion (solo admin)

### 14.1 Usuarios
- [ ] **TC-14.1.1** GET /users muestra lista de usuarios
- [ ] **TC-14.1.2** Crear usuario: nombre, email (unico), rol, sucursal, contraseña (min 8, confirmada)
- [ ] **TC-14.1.3** Editar usuario: cambiar nombre, email, rol, sucursal
- [ ] **TC-14.1.4** Cambiar contraseña: nueva contraseña (min 8, confirmada)
- [ ] **TC-14.1.5** Activar/desactivar usuario: toggle activo
- [ ] **TC-14.1.6** No se puede desactivar al ultimo admin activo
- [ ] **TC-14.1.7** No se puede cambiar rol del ultimo admin a otro rol
- [ ] **TC-14.1.8** No se puede eliminar al ultimo admin
- [ ] **TC-14.1.9** No se puede eliminar la propia cuenta

### 14.2 Sucursales
- [ ] **TC-14.2.1** GET /branches muestra lista de sucursales
- [ ] **TC-14.2.2** Crear sucursal: nombre (unico), direccion, telefono, email
- [ ] **TC-14.2.3** Editar sucursal: actualizar datos
- [ ] **TC-14.2.4** Eliminar sucursal: bloqueado si tiene usuarios asignados
- [ ] **TC-14.2.5** Activar/desactivar sucursal

### 14.3 Tipos de UN
- [ ] **TC-14.3.1** GET /un-types muestra lista de tipos
- [ ] **TC-14.3.2** Crear tipo: codigo (unico, uppercase), nombre, categoria (repair/paint/dm/parts/other/service)
- [ ] **TC-14.3.3** Editar tipo: actualizar datos y estado activo
- [ ] **TC-14.3.4** Eliminar tipo: bloqueado si esta en uso en OTs
- [ ] **TC-14.3.5** Orden de visualizacion configurable (sort_order 1-999)

### 14.4 Catalogo de Servicios
- [ ] **TC-14.4.1** GET /service-items muestra catalogo
- [ ] **TC-14.4.2** Crear servicio: codigo, descripcion, tipo, precio default, activo
- [ ] **TC-14.4.3** Editar servicio
- [ ] **TC-14.4.4** Eliminar servicio
- [ ] **TC-14.4.5** Crear tipo de servicio (ServiceItemType)
- [ ] **TC-14.4.6** Busqueda API: /api/service-items/search?q= devuelve JSON

### 14.5 Catalogo de Partes
- [ ] **TC-14.5.1** GET /parts muestra catalogo de partes
- [ ] **TC-14.5.2** Crear parte: nombre, categoria
- [ ] **TC-14.5.3** Editar parte
- [ ] **TC-14.5.4** Eliminar parte
- [ ] **TC-14.5.5** Busqueda API: /api/parts/search?q= devuelve JSON

### 14.6 Marcas y Modelos de Vehiculos
- [ ] **TC-14.6.1** GET /vehicle-brands muestra lista de marcas
- [ ] **TC-14.6.2** Crear marca con nombre unico
- [ ] **TC-14.6.3** Agregar modelos anidados a una marca
- [ ] **TC-14.6.4** Eliminar modelo
- [ ] **TC-14.6.5** Eliminar marca (si no tiene modelos en uso)
- [ ] **TC-14.6.6** API: /api/vehicle-brands devuelve JSON
- [ ] **TC-14.6.7** API: /api/vehicle-brands/{id}/models devuelve JSON de modelos

### 14.7 Roles
- [ ] **TC-14.7.1** GET /roles muestra roles del sistema y personalizados
- [ ] **TC-14.7.2** Roles del sistema (admin, recepcion, taller) no se pueden eliminar
- [ ] **TC-14.7.3** Crear rol personalizado: nombre, label, color badge, permisos
- [ ] **TC-14.7.4** Editar rol personalizado: cambiar permisos
- [ ] **TC-14.7.5** No se pueden modificar permisos de roles del sistema
- [ ] **TC-14.7.6** No se puede eliminar rol con usuarios asignados
- [ ] **TC-14.7.7** GET /users/permissions muestra matriz de permisos

### 14.8 Perfil y Empresa
- [ ] **TC-14.8.1** GET /profile muestra formulario de perfil + configuracion empresa
- [ ] **TC-14.8.2** Actualizar nombre y email del usuario
- [ ] **TC-14.8.3** Cambiar contraseña: requiere contraseña actual + nueva (min 8, confirmada)
- [ ] **TC-14.8.4** Empresa: nombre, RUT, direccion, telefono, email, logo
- [ ] **TC-14.8.5** Subir logo: imagen max 2MB, se almacena en storage/logos
- [ ] **TC-14.8.6** Dias validez presupuesto (1-365)
- [ ] **TC-14.8.7** Folio counter (>= 1)

---

## 15. SLA / Control de Tiempos

### 15.1 Dashboard SLA
- [ ] **TC-15.1.1** GET /sla muestra dashboard de SLA
- [ ] **TC-15.1.2** OTs activas ordenadas por urgencia (vencidas primero)
- [ ] **TC-15.1.3** Indicadores: OK (verde), Warning (amarillo ≥75% limite), Overdue (rojo >limite)
- [ ] **TC-15.1.4** Muestra dias habiles en estado actual

### 15.2 Configuracion SLA
- [ ] **TC-15.2.1** Formulario con limites por estado (dias habiles, 1-365)
- [ ] **TC-15.2.2** Guardar → actualiza Company.stage_sla
- [ ] **TC-15.2.3** Valores default: intake=2, budget_sent=5, approved=3, waiting_parts=15, in_repair=10, completed=2, delivered=3

### 15.3 Calculo de dias habiles
- [ ] **TC-15.3.1** Excluye sabados y domingos
- [ ] **TC-15.3.2** Excluye feriados cargados en el sistema
- [ ] **TC-15.3.3** OTs facturadas no muestran SLA (urgencia 'none')

---

## 16. Seguimiento (Follow-Up)

### 16.1 Vista de seguimiento
- [ ] **TC-16.1.1** GET /work-orders/seguimiento muestra OTs en estados no finales
- [ ] **TC-16.1.2** Calcula fecha vencimiento basada en quotation_validity_days
- [ ] **TC-16.1.3** Badges de urgencia: OK, Warning (≤7 dias), Critical (≤3 dias), Overdue
- [ ] **TC-16.1.4** Paginacion funciona

---

## 17. Aseguradoras y Liquidadores

### 17.1 Aseguradoras
- [ ] **TC-17.1.1** GET /insurance-companies muestra lista
- [ ] **TC-17.1.2** Crear: nombre unico
- [ ] **TC-17.1.3** Nombre duplicado → error
- [ ] **TC-17.1.4** Editar nombre
- [ ] **TC-17.1.5** Eliminar aseguradora
- [ ] **TC-17.1.6** Muestra conteo de liquidadores asociados

### 17.2 Liquidadores
- [ ] **TC-17.2.1** GET /liquidators muestra lista
- [ ] **TC-17.2.2** Crear: nombre, aseguradora (requerida), telefono, email
- [ ] **TC-17.2.3** Editar liquidador
- [ ] **TC-17.2.4** Eliminar liquidador

---

## 18. Permisos por Rol

### 18.1 Rol admin
- [ ] **TC-18.1.1** Admin ve todos los menus de administracion
- [ ] **TC-18.1.2** Admin puede crear, editar, eliminar OTs
- [ ] **TC-18.1.3** Admin puede gestionar usuarios, sucursales, roles
- [ ] **TC-18.1.4** Admin puede acceder a todos los reportes
- [ ] **TC-18.1.5** Admin puede ver datos de todas las sucursales

### 18.2 Rol recepcion
- [ ] **TC-18.2.1** Recepcion puede crear y editar OTs
- [ ] **TC-18.2.2** Recepcion NO puede eliminar OTs
- [ ] **TC-18.2.3** Recepcion puede crear y editar clientes/vehiculos
- [ ] **TC-18.2.4** Recepcion NO puede eliminar clientes/vehiculos
- [ ] **TC-18.2.5** Recepcion puede ver reportes
- [ ] **TC-18.2.6** Recepcion NO ve menu de administracion (usuarios, sucursales, roles, catalogos)
- [ ] **TC-18.2.7** Recepcion puede gestionar aseguradoras y liquidadores

### 18.3 Rol taller
- [ ] **TC-18.3.1** Taller solo puede ver OTs (lectura)
- [ ] **TC-18.3.2** Taller NO puede crear/editar OTs
- [ ] **TC-18.3.3** Taller puede ver clientes y vehiculos (lectura)
- [ ] **TC-18.3.4** Taller NO puede crear/editar clientes ni vehiculos
- [ ] **TC-18.3.5** Taller NO ve menu de administracion
- [ ] **TC-18.3.6** Taller NO puede acceder a reportes
- [ ] **TC-18.3.7** Taller puede ver su perfil

### 18.4 Usuario inactivo
- [ ] **TC-18.4.1** Usuario desactivado → al intentar navegar es deslogueado automaticamente

---

## 19. Multi-Sucursal

### 19.1 Selector de sucursal (admin)
- [ ] **TC-19.1.1** Admin ve selector de sucursal en sidebar
- [ ] **TC-19.1.2** "Todas las sucursales" muestra datos de todas las sucursales
- [ ] **TC-19.1.3** Seleccionar una sucursal filtra OTs por esa sucursal
- [ ] **TC-19.1.4** Seleccionar sucursal filtra clientes por esa sucursal
- [ ] **TC-19.1.5** Seleccionar sucursal filtra vehiculos por esa sucursal

### 19.2 Aislamiento por sucursal (no-admin)
- [ ] **TC-19.2.1** Usuario recepcion solo ve datos de su sucursal
- [ ] **TC-19.2.2** Usuario taller solo ve datos de su sucursal
- [ ] **TC-19.2.3** No hay selector de sucursal para usuarios no-admin

---

## 20. Responsive (Mobile/Tablet)

### 20.1 Layout responsive
- [ ] **TC-20.1.1** Viewport < 992px: aparece barra superior con hamburguesa + logo + avatar
- [ ] **TC-20.1.2** Click hamburguesa → sidebar se desliza desde la izquierda con overlay
- [ ] **TC-20.1.3** Click overlay → sidebar se cierra
- [ ] **TC-20.1.4** Click en link del menu → sidebar se cierra y navega

### 20.2 Contenido responsive
- [ ] **TC-20.2.1** Dashboard: stats se apilan en 3 columnas (tablet) o 2 (mobile)
- [ ] **TC-20.2.2** Tablas son scrolleables horizontalmente
- [ ] **TC-20.2.3** Formularios ocupan ancho completo
- [ ] **TC-20.2.4** OT show: hero compacto, totales en 1 columna en mobile
- [ ] **TC-20.2.5** Filtros se apilan verticalmente en mobile

---

## 21. Editar Orden de Trabajo

### 21.1 Formulario de edicion
- [ ] **TC-21.1.1** GET /work-orders/{id}/edit carga con datos actuales
- [ ] **TC-21.1.2** Cliente y vehiculo pre-seleccionados
- [ ] **TC-21.1.3** Items existentes cargados en la tabla
- [ ] **TC-21.1.4** Etiquetas existentes pre-seleccionadas

### 21.2 Actualizacion
- [ ] **TC-21.2.1** Modificar datos del header → se actualiza
- [ ] **TC-21.2.2** Agregar nuevos items → se guardan
- [ ] **TC-21.2.3** Eliminar items existentes → se eliminan y recrean
- [ ] **TC-21.2.4** Cambiar etiquetas → se actualizan
- [ ] **TC-21.2.5** Totales se recalculan al guardar

### 21.3 Restricciones
- [ ] **TC-21.3.1** OT facturada: no se puede acceder a editar (redirige o error)
- [ ] **TC-21.3.2** Validaciones identicas a creacion

---

## 22. Eliminar Orden de Trabajo

- [ ] **TC-22.1** Solo admin puede eliminar OTs
- [ ] **TC-22.2** Confirmar eliminacion → OT eliminada con items, eventos y tags
- [ ] **TC-22.3** Redirige a listado con mensaje de exito
- [ ] **TC-22.4** Recepcion no ve boton de eliminar

---

## Notas

- La BD se puede resetear en cualquier momento con: `php artisan migrate:fresh --seed`
- Credenciales de admin: admin@gestaller.cl / admin123
- PHP path en Laragon: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`
