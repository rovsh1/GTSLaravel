import '~resources/js/plugins/password-viewer/style.scss'

$.fn.passwordViewer = function () {
    return this.each(function () {
        const $input = $(this)
        if (!$input.attr('type') || $input.attr('type') !== 'password') {
            console.error('Element must be input with type password')
            return
        }
        const $container = $('<div class="password-container"></div>')
        const $toggle = $('<div class="password-toggle"><span>&#x1F441;</span></div>')
        $input.css('padding-right', '1.5rem')
        $input.wrap($container)
        $toggle.insertAfter($input)
        if ($input.val() === '') $toggle.hide()
        $input.on('input paste', function () {
            if ($input.val() === '') $toggle.hide()
            else {
                if ($input.attr('type') === 'password')
                    $toggle.removeClass('active')
                else
                    $toggle.addClass('active')
                $toggle.show()
            }
        })
        $toggle.on('click', function () {
            const type = $input.attr('type')
            if (type === 'password') {
                $input.attr('type', 'text')
                $toggle.addClass('active')
            } else {
                $input.attr('type', 'password')
                $toggle.removeClass('active')
            }
        })
    })
}