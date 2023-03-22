import axios from '../app/api'

$.fn.childCombo = function (options) {
    options = Object.assign({
        dataIndex: 'parent_id',
        resultIndex: 'data',
        hideEmpty: false,
        allowEmpty: false,
        emptyItem: '',
        disabledText: '',
        emptyText: 'Пусто',
        hidden: false,
        dateRange: false
    }, options);
    var parent = $(options.parent);
    if (!parent.length) {
        return $(this);
    }

    var child = $(this);
    var isMultiple = child.attr('multiple');

    if (child.is('input[type="hidden"]')) {
        options.hidden = true;
        var c = $(`<select class="form-select" name="${child.attr('name')}"></select>`);
        child.after(c);
        child.remove();
        child = c;
    }

    var trigger = function (fn, arg) {
        if (!options[fn])
            return;
        options[fn].call(child, arg);
    };

    var onchange = function () {
        if (options.dateRange && parent.val().length < 14)
            return;

        trigger('change');

        child.prop('disabled', true);
        let isEmpty = parent.val() === null || parent.val() === '';
        if (!options.allowEmpty && isEmpty) {
            child.html('<option value="">' + options.disabledText + '</option>');
            if (options.hideEmpty) {
                child.parent().hide();
            }
            trigger('load', []);
            return;
        }

        child.parent().show();

        var value = options.value || child.val();
        var valTemp = [];
        var data = $.extend({}, options.data);
        if (!isEmpty) {
            data[options.dataIndex] = parent.val();
        }
        if (value) {
            valTemp = isMultiple ? value : [value];
        }
        //delete options.value;

        child.html("<option value=''>Загрузка</option>");

        axios.get(options.url, {params: data}).then((result) => {
            child.html('');
            var items = result[options.resultIndex];
            var val = [], i, l = items.length;
            if (l === 0) {
                if (false !== options.emptyText)
                    child.append('<option value="">' + options.emptyText + '</option>');
                if (options.hideEmpty)
                    child.parent().hide();
                trigger('load', items);
                return;
            }

            if (false !== options.emptyItem) {
                child.append('<option value="">' + options.emptyItem + '</option>');
            }

            for (i = 0; i < l; i++) {
                if (in_array(items[i].id, valTemp)) {
                    val[val.length] = items[i].id;
                }

                child.append("<option value='" + items[i].id + "'>" + items[i].name + "</option>");
            }

            child.prop("disabled", false);

            if (val.length) {
                child.val(isMultiple ? val : val[0]);
            }

            child.change();
            trigger('load', items);
        });
    };

    parent.change(onchange);

    onchange();

    return child;
};
