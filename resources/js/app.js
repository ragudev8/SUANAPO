import './bootstrap';
import * as bootstrap from 'bootstrap';

function normalizeText(value) {
  return String(value || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');
}

function initSearchSelect(root) {
  const hidden = root.querySelector('input[type="hidden"]');
  const input = root.querySelector('.search-select-input');
  const menu = root.querySelector('.search-select-menu');
  const clear = root.querySelector('.search-select-clear');
  const items = JSON.parse(root.dataset.items || '[]');

  function setValue(value, label) {
    hidden.value = value;
    input.value = label || '';
    menu.hidden = true;
  }

  function render(term = '') {
    const normalizedTerm = normalizeText(term);
    const matches = items
      .filter((item) => normalizeText(item.label).includes(normalizedTerm))
      .slice(0, 12);

    menu.querySelectorAll('.search-select-option[data-generated="1"]').forEach((node) => node.remove());

    matches.forEach((item) => {
      const option = document.createElement('button');
      option.type = 'button';
      option.className = 'search-select-option';
      option.dataset.generated = '1';
      option.dataset.value = item.value;
      option.dataset.label = item.label;
      option.textContent = item.label;
      menu.appendChild(option);
    });

    if (!matches.length && term) {
      const empty = document.createElement('div');
      empty.className = 'text-secondary small px-2 py-2 search-select-option';
      empty.dataset.generated = '1';
      empty.textContent = 'Sin resultados';
      menu.appendChild(empty);
    }

    menu.hidden = false;
  }

  input.addEventListener('focus', () => render(input.value));
  input.addEventListener('input', () => {
    hidden.value = '';
    render(input.value);
  });

  menu.addEventListener('click', (event) => {
    const option = event.target.closest('button[data-value]');
    if (!option) return;
    setValue(option.dataset.value, option.dataset.label);
  });

  clear.addEventListener('click', () => {
    setValue('', '');
    input.focus();
    render('');
  });

  root.closest('form')?.addEventListener('submit', (event) => {
    if (root.dataset.required === '1' && !hidden.value) {
      event.preventDefault();
      input.setCustomValidity('Seleccione una opcion de la lista.');
      input.reportValidity();
      input.focus();
      render(input.value);
      return;
    }

    input.setCustomValidity('');
  });

  document.addEventListener('click', (event) => {
    if (!root.contains(event.target)) {
      menu.hidden = true;
    }
  });
}

document.querySelectorAll('[data-search-select]').forEach(initSearchSelect);

const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
if (sidebarToggle) {
  const applySidebarState = () => {
    const collapsed = localStorage.getItem('anapo.sidebar.collapsed') === '1';
    document.body.classList.toggle('sidebar-collapsed', collapsed);
    sidebarToggle.querySelector('i')?.classList.toggle('bi-layout-sidebar-inset', !collapsed);
    sidebarToggle.querySelector('i')?.classList.toggle('bi-layout-sidebar', collapsed);
    sidebarToggle.setAttribute('aria-label', collapsed ? 'Mostrar menu' : 'Ocultar menu');
  };

  sidebarToggle.addEventListener('click', () => {
    const collapsed = localStorage.getItem('anapo.sidebar.collapsed') === '1';
    localStorage.setItem('anapo.sidebar.collapsed', collapsed ? '0' : '1');
    applySidebarState();
  });

  applySidebarState();
}

const confirmModalElement = document.getElementById('confirmActionModal');
if (confirmModalElement) {
  const confirmModal = new bootstrap.Modal(confirmModalElement);
  const confirmMessage = confirmModalElement.querySelector('[data-confirm-message]');
  const confirmAccept = confirmModalElement.querySelector('[data-confirm-accept]');
  let pendingForm = null;

  document.querySelectorAll('form[onsubmit*="confirm"]').forEach((form) => {
    const inlineConfirm = form.getAttribute('onsubmit') || '';
    const match = inlineConfirm.match(/confirm\(['"](.+?)['"]\)/);
    const message = match?.[1] || 'Desea continuar?';

    form.removeAttribute('onsubmit');
    form.dataset.confirmMessage = message;

    form.addEventListener('submit', (event) => {
      if (form.dataset.confirmed === '1') {
        return;
      }

      event.preventDefault();
      pendingForm = form;
      confirmMessage.textContent = form.dataset.confirmMessage || 'Desea continuar?';
      confirmModal.show();
    });
  });

  confirmAccept?.addEventListener('click', () => {
    if (!pendingForm) return;

    pendingForm.dataset.confirmed = '1';
    confirmModal.hide();
    pendingForm.requestSubmit();
    pendingForm = null;
  });
}
