import './style.css'

const seed = {
  employees: [
    { id: 1, dni: '74281935', nom: 'María Fernanda López', tel: '987 654 321', estado: 'Activo', user: 'mlopez' },
    { id: 2, dni: '70981246', nom: 'Carlos Ramírez', tel: '956 231 876', estado: 'Activo', user: 'cramirez' },
  ],
  clients: [
    { id: 1, dni: '70451238', nom: 'Comercial Andina SAC', dir: 'Av. Arequipa 1240', es: 'Activo' },
    { id: 2, dni: '46872109', nom: 'Lucía Torres', dir: 'Jr. Los Olivos 451', es: 'Activo' },
    { id: 3, dni: '10293847', nom: 'Inversiones Norte EIRL', dir: 'Calle Lima 220', es: 'Activo' },
  ],
  products: [
    { id: 1, nom: 'Laptop Lenovo IdeaPad', pre: 2499.9, stock: 14, estado: 'Disponible' },
    { id: 2, nom: 'Monitor LG 24 pulgadas', pre: 679.0, stock: 28, estado: 'Disponible' },
    { id: 3, nom: 'Teclado mecánico RGB', pre: 189.9, stock: 7, estado: 'Bajo stock' },
    { id: 4, nom: 'Mouse inalámbrico Logitech', pre: 89.9, stock: 35, estado: 'Disponible' },
  ],
}

const stored = JSON.parse(localStorage.getItem('sistemas-ventas-data') || 'null')
const state = {
  ...seed,
  ...(stored || {}),
  sales: stored?.sales || [],
  view: 'dashboard',
  cart: [],
  selectedClient: null,
  selectedProduct: null,
  series: localStorage.getItem('sistemas-ventas-series') || '00000001',
  editing: null,
}

const app = document.querySelector('#app')
const money = (value) => new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(value)
const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character])
const persist = () => localStorage.setItem('sistemas-ventas-data', JSON.stringify({ employees: state.employees, clients: state.clients, products: state.products, sales: state.sales }))
const nextId = (items) => Math.max(0, ...items.map((item) => item.id)) + 1
const icon = (name) => ({ grid: '▦', users: '♙', package: '□', cart: '＋', search: '⌕', edit: '✎', trash: '×', arrow: '→', check: '✓' })[name] || ''

function render() {
  const active = state.view
  app.innerHTML = `
    <div class="shell">
      <aside class="sidebar">
        <div class="brand"><span class="brand-mark">sv</span><span><strong>Sistemas</strong><small>VENTAS</small></span></div>
        <div class="workspace-label">ESPACIO DE TRABAJO</div>
        <nav class="nav-list">
          ${navItem('dashboard', 'Resumen', 'grid')}
          ${navItem('sales', 'Nueva venta', 'cart')}
          ${navItem('products', 'Productos', 'package')}
          ${navItem('clients', 'Clientes', 'users')}
          ${navItem('employees', 'Empleados', 'users')}
        </nav>
        <div class="sidebar-foot"><span class="online-dot"></span><span><strong>Sesión activa</strong><small>Administrador</small></span><button class="icon-button" title="Cerrar sesión">↪</button></div>
      </aside>
      <main class="main-content">
        <header class="topbar"><div class="breadcrumbs"><span>Panel</span><span>/</span><strong>${viewTitle(active)}</strong></div><div class="top-actions"><button class="bell" title="Notificaciones">♢<i></i></button><div class="avatar">AG</div></div></header>
        <div class="page-content">${renderView()}</div>
      </main>
    </div>`
  bindEvents()
}

function navItem(view, label, iconName) { return `<button class="nav-item ${state.view === view ? 'active' : ''}" data-view="${view}"><span>${icon(iconName)}</span>${label}${view === 'sales' ? '<b>+</b>' : ''}</button>` }
function viewTitle(view) { return ({ dashboard: 'Resumen general', sales: 'Registrar venta', products: 'Catálogo de productos', clients: 'Directorio de clientes', employees: 'Equipo de trabajo' })[view] }
function renderView() {
  if (state.view === 'dashboard') return renderDashboard()
  if (state.view === 'sales') return renderSales()
  return renderDirectory(state.view)
}
function renderDashboard() {
  const inventoryValue = state.products.reduce((sum, product) => sum + product.pre * product.stock, 0)
  return `<section class="welcome"><div><p class="eyebrow">JUEVES, 24 DE SEPTIEMBRE DE 2026</p><h1>Hola, administrador <span>👋</span></h1><p class="muted">Aquí tienes una mirada rápida de tu operación.</p></div><button class="button primary" data-view="sales">${icon('cart')} Registrar venta</button></section>
    <div class="stats-grid"><div class="stat-card"><div class="stat-icon blue">${icon('cart')}</div><div><span>Ventas del mes</span><strong>S/ 12,840.50</strong><small class="positive">↑ 12.5% <em>vs. mes anterior</em></small></div></div><div class="stat-card"><div class="stat-icon green">${icon('users')}</div><div><span>Clientes registrados</span><strong>${state.clients.length}</strong><small class="positive">↑ 8.2% <em>vs. mes anterior</em></small></div></div><div class="stat-card"><div class="stat-icon orange">${icon('package')}</div><div><span>Productos en catálogo</span><strong>${state.products.length}</strong><small class="neutral">${state.products.filter((product) => product.stock < 10).length} requieren atención</small></div></div><div class="stat-card"><div class="stat-icon purple">▣</div><div><span>Valor del inventario</span><strong>${money(inventoryValue)}</strong><small class="neutral">Stock valorizado</small></div></div></div>
    <div class="dashboard-grid"><section class="panel chart-panel"><div class="panel-heading"><div><h2>Rendimiento de ventas</h2><p class="muted">Ingresos de los últimos 7 días</p></div><select><option>Esta semana</option><option>Este mes</option></select></div><div class="chart"><div class="chart-labels"><span>S/ 2k</span><span>S/ 1k</span><span>S/ 0</span></div><div class="chart-area"><div class="grid-lines"></div><svg viewBox="0 0 700 220" preserveAspectRatio="none" aria-label="Gráfico de ventas"><defs><linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#2c7be5" stop-opacity=".28"/><stop offset="1" stop-color="#2c7be5" stop-opacity="0"/></linearGradient></defs><path d="M0,170 C42,160 55,125 100,138 S155,153 200,105 S260,118 300,122 S350,80 400,95 S450,132 500,85 S550,110 600,52 S650,72 700,28 L700,220 L0,220Z" fill="url(#chartFill)"/><path d="M0,170 C42,160 55,125 100,138 S155,153 200,105 S260,118 300,122 S350,80 400,95 S450,132 500,85 S550,110 600,52 S650,72 700,28" fill="none" stroke="#2c7be5" stroke-width="3"/></svg><div class="chart-days"><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span></div></div></div></section><section class="panel quick-panel"><div class="panel-heading"><div><h2>Accesos rápidos</h2><p class="muted">Tareas frecuentes</p></div></div><button class="quick-action" data-view="sales"><span class="quick-icon blue">${icon('cart')}</span><span><strong>Registrar una venta</strong><small>Crear una nueva transacción</small></span>${icon('arrow')}</button><button class="quick-action" data-view="products"><span class="quick-icon orange">${icon('package')}</span><span><strong>Ver productos</strong><small>Gestionar catálogo e inventario</small></span>${icon('arrow')}</button><button class="quick-action" data-view="clients"><span class="quick-icon green">${icon('users')}</span><span><strong>Buscar cliente</strong><small>Consultar directorio de clientes</small></span>${icon('arrow')}</button></section></div>`
}

function renderDirectory(type) {
  const config = { employees: { label: 'empleado', title: 'Empleados', description: 'Administra los usuarios que operan el sistema.', fields: [['dni', 'DNI'], ['nom', 'Nombres completos'], ['tel', 'Teléfono'], ['estado', 'Estado'], ['user', 'Usuario']], columns: ['ID', 'DNI', 'NOMBRES', 'TELÉFONO', 'ESTADO', 'USUARIO'] }, clients: { label: 'cliente', title: 'Clientes', description: 'Consulta y organiza la información de tus clientes.', fields: [['dni', 'DNI / RUC'], ['nom', 'Nombre o razón social'], ['dir', 'Dirección'], ['es', 'Estado']], columns: ['ID', 'DNI / RUC', 'NOMBRE', 'DIRECCIÓN', 'ESTADO'] }, products: { label: 'producto', title: 'Productos', description: 'Mantén tu catálogo y existencias siempre al día.', fields: [['nom', 'Nombre del producto'], ['pre', 'Precio'], ['stock', 'Stock'], ['estado', 'Estado']], columns: ['ID', 'PRODUCTO', 'PRECIO', 'STOCK', 'ESTADO'] } }[type]
  const items = state[type]
  return `<section class="section-header"><div><p class="eyebrow">GESTIÓN / ${config.title.toUpperCase()}</p><h1>${config.title}</h1><p class="muted">${config.description}</p></div><button class="button primary" data-open-form="${type}">＋ Nuevo ${config.label}</button></section><div class="toolbar"><label class="search-box">${icon('search')}<input data-filter="${type}" placeholder="Buscar ${config.label}..." /></label><span class="result-count">${items.length} registros</span></div><section class="panel table-panel"><div class="table-wrap"><table><thead><tr>${config.columns.map((column) => `<th>${column}</th>`).join('')}<th>ACCIONES</th></tr></thead><tbody>${items.length ? items.map((item) => rowFor(type, item)).join('') : `<tr><td colspan="${config.columns.length + 1}" class="empty">No hay registros todavía.</td></tr>`}</tbody></table></div></section>${state.editing?.type === type ? renderModal(type, config) : ''}`
}
function rowFor(type, item) { const cells = type === 'employees' ? [item.id, item.dni, item.nom, item.tel, status(item.estado), item.user] : type === 'clients' ? [item.id, item.dni, item.nom, item.dir, status(item.es)] : [item.id, item.nom, money(item.pre), item.stock, status(item.estado, item.stock < 10)] ; return `<tr>${cells.map((cell) => `<td>${cell}</td>`).join('')}<td><div class="row-actions"><button class="text-button edit" data-edit="${type}" data-id="${item.id}">${icon('edit')} Editar</button><button class="text-button danger delete" data-delete="${type}" data-id="${item.id}">${icon('trash')}</button></div></td></tr>` }
function status(value, warning = false) { return `<span class="status ${warning ? 'warning' : String(value).toLowerCase() === 'activo' || String(value).toLowerCase() === 'disponible' ? 'success' : ''}">${escapeHtml(value)}</span>` }
function renderModal(type, config) { const current = state.editing.item || {}; return `<div class="modal-backdrop"><form class="modal" data-form="${type}"><button type="button" class="modal-close" data-close>×</button><p class="eyebrow">${state.editing.item ? 'EDITAR' : 'NUEVO'}</p><h2>${state.editing.item ? 'Editar' : 'Agregar'} ${config.label}</h2><div class="form-grid">${config.fields.map(([field, label]) => `<label>${label}<input name="${field}" type="${field === 'pre' || field === 'stock' ? 'number' : 'text'}" value="${escapeHtml(current[field] || '')}" required></label>`).join('')}</div><div class="modal-actions"><button type="button" class="button secondary" data-close>Cancelar</button><button class="button primary">Guardar ${config.label}</button></div></form></div>` }

function renderSales() { const total = state.cart.reduce((sum, item) => sum + item.subtotal, 0); return `<section class="section-header"><div><p class="eyebrow">OPERACIONES / VENTAS</p><h1>Registrar venta</h1><p class="muted">Busca un cliente, agrega productos y confirma la transacción.</p></div><div class="series"><span>NÚMERO DE SERIE</span><strong>${state.series}</strong></div></section><div class="sales-layout"><section class="panel sale-form"><div class="form-section"><h2>Datos del cliente</h2><label class="search-box full">${icon('search')}<input id="client-search" placeholder="Buscar por DNI o nombre" value="${escapeHtml(state.selectedClient?.dni || '')}" /><button type="button" id="find-client">Buscar</button></label>${state.selectedClient ? `<div class="selected-result"><span class="avatar small">${initials(state.selectedClient.nom)}</span><span><strong>${escapeHtml(state.selectedClient.nom)}</strong><small>${escapeHtml(state.selectedClient.dni)} · ${escapeHtml(state.selectedClient.dir)}</small></span><button type="button" id="clear-client">×</button></div>` : '<p class="form-hint">Selecciona un cliente antes de generar la venta.</p>'}</div><div class="form-section"><h2>Agregar producto</h2><div class="product-search"><label class="search-box"><input id="product-search" placeholder="Código o nombre del producto" value="${escapeHtml(state.selectedProduct?.nom || '')}" /><button type="button" id="find-product">Buscar</button></label></div>${state.selectedProduct ? `<div class="product-preview"><div class="product-badge">${icon('package')}</div><div><strong>${escapeHtml(state.selectedProduct.nom)}</strong><small>Código #${state.selectedProduct.id} · Stock disponible: ${state.selectedProduct.stock}</small></div><strong class="product-price">${money(state.selectedProduct.pre)}</strong></div><div class="quantity-row"><label>Cantidad<input id="quantity" type="number" min="1" max="${state.selectedProduct.stock}" value="1"></label><button class="button secondary" type="button" id="add-product">＋ Agregar al detalle</button></div>` : '<p class="form-hint">Busca un producto para añadirlo al detalle de venta.</p>'}</div></section><section class="panel detail-panel"><div class="panel-heading"><div><h2>Detalle de venta</h2><p class="muted">${state.cart.length} productos agregados</p></div><button class="text-button danger" id="clear-cart" ${state.cart.length ? '' : 'disabled'}>Vaciar</button></div>${state.cart.length ? `<div class="cart-list">${state.cart.map((item, index) => `<div class="cart-row"><span class="item-number">${String(index + 1).padStart(2, '0')}</span><span><strong>${escapeHtml(item.description)}</strong><small>${item.quantity} × ${money(item.price)}</small></span><strong>${money(item.subtotal)}</strong><button class="text-button danger" data-remove-cart="${index}">${icon('trash')}</button></div>`).join('')}</div>` : `<div class="empty-cart"><div>${icon('cart')}</div><strong>Tu detalle está vacío</strong><span>Agrega productos desde el formulario</span></div>`}<div class="total-box"><span>Total a pagar</span><strong>${money(total)}</strong></div><button class="button primary full-button" id="generate-sale" ${!state.selectedClient || !state.cart.length ? 'disabled' : ''}>${icon('check')} Generar venta</button><button class="button-link" id="cancel-sale">Cancelar operación</button></section></div>` }
function initials(name) { return name.split(' ').slice(0, 2).map((part) => part[0]).join('').toUpperCase() }

function bindEvents() {
  document.querySelectorAll('[data-view]').forEach((button) => button.addEventListener('click', () => { state.view = button.dataset.view; state.editing = null; render() }))
  document.querySelectorAll('[data-open-form]').forEach((button) => button.addEventListener('click', () => { state.editing = { type: button.dataset.openForm, item: null }; render() }))
  document.querySelectorAll('[data-edit]').forEach((button) => button.addEventListener('click', () => { const item = state[button.dataset.edit].find((entry) => entry.id === Number(button.dataset.id)); state.editing = { type: button.dataset.edit, item }; render() }))
  document.querySelectorAll('[data-delete]').forEach((button) => button.addEventListener('click', () => { if (confirm('¿Eliminar este registro?')) { state[button.dataset.delete] = state[button.dataset.delete].filter((item) => item.id !== Number(button.dataset.id)); persist(); render() } }))
  document.querySelectorAll('[data-close]').forEach((button) => button.addEventListener('click', () => { state.editing = null; render() }))
  document.querySelectorAll('[data-filter]').forEach((input) => input.addEventListener('input', () => { const term = input.value.toLowerCase(); document.querySelectorAll('tbody tr').forEach((row) => { row.hidden = !row.textContent.toLowerCase().includes(term) }) }))
  const form = document.querySelector('[data-form]'); if (form) form.addEventListener('submit', (event) => { event.preventDefault(); const values = Object.fromEntries(new FormData(form)); const type = state.editing.type; const item = { ...values, id: state.editing.item?.id || nextId(state[type]) }; if (type === 'products') { item.pre = Number(item.pre); item.stock = Number(item.stock) } if (state.editing.item) state[type] = state[type].map((entry) => entry.id === item.id ? item : entry); else state[type].push(item); persist(); state.editing = null; render() })
  bindSalesEvents()
}
function bindSalesEvents() {
  const findClient = () => {
    const query = document.querySelector('#client-search')?.value.trim().toLowerCase()
    state.selectedClient = query
      ? state.clients.find((client) => client.dni.toLowerCase() === query || client.nom.toLowerCase().includes(query)) || null
      : null
    render()
  }
  const findProduct = () => {
    const query = document.querySelector('#product-search')?.value.trim().toLowerCase()
    state.selectedProduct = query
      ? state.products.find((product) => String(product.id) === query || product.nom.toLowerCase().includes(query)) || null
      : null
    render()
  }
  const findByEnter = (selector, callback) => document.querySelector(selector)?.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      event.preventDefault()
      callback()
    }
  })

  document.querySelector('#find-client')?.addEventListener('click', findClient)
  findByEnter('#client-search', findClient)
  document.querySelector('#find-product')?.addEventListener('click', findProduct)
  findByEnter('#product-search', findProduct)
  document.querySelector('#clear-client')?.addEventListener('click', () => {
    state.selectedClient = null
    render()
  })
  document.querySelector('#add-product')?.addEventListener('click', () => {
    const product = state.selectedProduct
    const quantity = Number(document.querySelector('#quantity')?.value)
    if (!product || !Number.isInteger(quantity) || quantity < 1) return

    const alreadyInCart = state.cart
      .filter((item) => item.productId === product.id)
      .reduce((sum, item) => sum + item.quantity, 0)
    if (quantity + alreadyInCart > product.stock) {
      alert(`Stock insuficiente. Disponible: ${product.stock - alreadyInCart}`)
      return
    }

    state.cart.push({
      productId: product.id,
      description: product.nom,
      price: product.pre,
      quantity,
      subtotal: quantity * product.pre,
    })
    state.selectedProduct = null
    render()
  })
  document.querySelectorAll('[data-remove-cart]').forEach((button) => button.addEventListener('click', () => {
    state.cart.splice(Number(button.dataset.removeCart), 1)
    render()
  }))
  document.querySelector('#clear-cart')?.addEventListener('click', () => {
    state.cart = []
    render()
  })
  document.querySelector('#cancel-sale')?.addEventListener('click', () => {
    state.cart = []
    state.selectedClient = null
    state.selectedProduct = null
    render()
  })
  document.querySelector('#generate-sale')?.addEventListener('click', () => {
    if (!state.selectedClient) {
      alert('Selecciona un cliente antes de generar la venta.')
      return
    }
    if (state.cart.length === 0) {
      alert('Agrega al menos un producto antes de generar la venta.')
      return
    }

    const quantities = state.cart.reduce((totals, item) => {
      totals.set(item.productId, (totals.get(item.productId) || 0) + item.quantity)
      return totals
    }, new Map())
    const insufficientProduct = [...quantities].find(([productId, quantity]) => {
      const product = state.products.find((entry) => entry.id === productId)
      return !product || quantity > product.stock
    })
    if (insufficientProduct) {
      alert('El stock cambió. Revisa las cantidades antes de generar la venta.')
      return
    }

    const total = state.cart.reduce((sum, item) => sum + item.subtotal, 0)
    state.sales.unshift({
      series: state.series,
      date: new Date().toISOString(),
      clientId: state.selectedClient.id,
      clientName: state.selectedClient.nom,
      items: state.cart.map((item) => ({ ...item })),
      total,
    })
    state.cart.forEach((item) => {
      const product = state.products.find((entry) => entry.id === item.productId)
      product.stock -= item.quantity
    })
    state.series = String(Number(state.series) + 1).padStart(8, '0')
    persist()
    localStorage.setItem('sistemas-ventas-series', state.series)
    alert(`Venta ${state.sales[0].series} generada por ${money(total)} para ${state.selectedClient.nom}`)
    state.cart = []
    state.selectedClient = null
    state.selectedProduct = null
    render()
  })
}

render()
