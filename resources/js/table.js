(($, DataTable) => {
    'use strict'

    class PluginTableManagement {
        constructor() {
            this.initDetailsControl()
        }

        initDetailsControl() {
            let $this = this;

            $(document).on('click', 'td.column-key-details-control .table-row-expand-icon', function (e) {
                e.preventDefault()
                const _self = $(e.currentTarget)
                const tableId = _self.closest('table').prop('id')
                const tr = $(this).closest('tr')
                const table = window.LaravelDataTables[tableId]
                const row = table.row(tr)

                let child = row.child
                if (_self.hasClass('expanded')) {
                    child.hide()
                    tr.removeClass('shown')
                    _self.removeClass('expanded')
                } else {
                    // Open this row
                    tr.addClass('shown')
                    _self.addClass('expanded')

                    const url = _self.data('url')
                    if (url) {
                        $.ajax({
                            type: _self.data('method') || 'GET',
                            url: url,
                            beforeSend: () => {
                                row.child('<div class="text-center"><div class="my-4 spinner-border text-info" role="status"></div></div>', 'table-details-control-content').show()
                            },
                            success: (res) => {
                                if (res.error) {
                                    Botble.showError(res.message)
                                } else {
                                    row.child(res?.data?.html || res?.data || res, 'table-details-control-content')

                                    $this.afterAppendChild(row)
                                }
                            },
                            error: (error) => {
                                Botble.handleError(error)
                            },
                        })
                    } else {
                        const detailContent = _self.closest('.table-details-wrapper').find('.table-details-control-main')

                        if (detailContent.length) {
                            row.child(detailContent.html(), 'table-details-control-content').show()
                        }
                    }
                }
            })
        }

        afterAppendChild(row) {
            let $child = row.child();
            if (window.htmldiff) {
                $.each($child.find('.html-diff-content'), function (index, item) {
                    $(item).html(htmldiff($(item).data('original'), $(item).html()))
                })
            }

            if (jQuery().fancybox) {    
                $child.find('.fancybox').fancybox({
                    openEffect: 'none',
                    closeEffect: 'none',
                    overlayShow: true,
                    overlayOpacity: 0.7,
                    helpers: {
                        media: {},
                    },
                })
            }

            if (jQuery().tooltip) {
                $child.find('[data-bs-toggle="tooltip"]').tooltip({ placement: 'top', boundary: 'window' })
            }
        }
    }

    $(() => {
        new PluginTableManagement()
        window.PluginTableManagement = PluginTableManagement
    })
})(jQuery, jQuery.fn.dataTable)
