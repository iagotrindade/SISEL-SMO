var config = {
  '.chosen-select'           : {},
  '.chosen-select-deselect'  : { allow_single_deselect: true },
  '.chosen-select-no-single' : { disable_search_threshold: 10 },
  '.chosen-select-no-results': { no_results_text: 'Nada encontrado!' },
  '.chosen-select-rtl'       : { rtl: true },
  '.chosen-select-width'     : { width: '95%' },
  '.chosen-select-placeholder': {placeholder_text_multiple: 'Selecione as opções'}
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
