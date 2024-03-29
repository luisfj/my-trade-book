$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
        $('#brand-app-name').toggleClass('hidde-me');

        if (typeof(Storage) !== "undefined") {
            // Save the state of the sidebar as "open"
            localStorage.setItem("sidebar", ($('#sidebar').hasClass('active') ? 'close' : ''));
        }



        // close dropdowns
        $('.collapse.in').toggleClass('in');
        // and also adjust aria-expanded attributes we use for the open/closed arrows
        // in our CSS
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    Inputmask.extendAliases({
        'valor': {
            alias:'decimal',
            autoUnmask: true,
            allowMinus: true,
            removeMaskOnSubmit: true,
            unmaskAsNumber: false,
            groupSeparator: '',
            autoGroup: false,
            digits: 2,
            rightAlign: false,
            radixPoint: ",",
            digitsOptional: true,
          //  prefix: 'USD ',
          onBeforeMask: function (value, opts) {
            var processedValue = value.replace('.', ",");
            return processedValue;
          }
        }
      });

      //Inputmask('valor').mask('.decimal-mask');
      $('.decimal-mask').inputmask('valor');
});
