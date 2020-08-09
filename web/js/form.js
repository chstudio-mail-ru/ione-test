$('body').on('change', '#searchform-mark', function() {
    var $this = $(this);
    var mark = $this.val();
    //var model = $('#searchform-model').val();
    var engine = $('#searchform-engine').val();
    var drive = $('#searchform-drive').val();

    if (engine.length < 1 && drive.length < 1) {
        window.location = '/'+mark;
    } else if (engine.length > 0 && drive.length < 1) {
        window.location = '/'+mark+'?engine='+engine
    } else if (drive.length > 0 && engine.length < 1) {
        window.location = '/'+mark+'?drive='+drive
    } else if (engine.length > 0 && drive.length > 0) {
        window.location = '/'+mark+'?engine='+engine+'&drive='+drive;
    }
});

$('body').on('change', '#searchform-model', function() {
    var $this = $(this);
    var mark = $('#searchform-mark').val();
    var model = $this.val();
    var engine = $('#searchform-engine').val();
    var drive = $('#searchform-drive').val();

    if (engine.length < 1 && drive.length < 1) {
        window.location = '/'+mark+'/'+model;
    } else if (engine.length > 0 && drive.length < 1) {
        window.location = '/'+mark+'/'+model+'?engine='+engine
    } else if (drive.length > 0 && engine.length < 1) {
        window.location = '/'+mark+'/'+model+'?drive='+drive
    } else if (engine.length > 0 && drive.length > 0) {
        window.location = '/'+mark+'/'+model+'?engine=' + engine + '&drive=' + drive;
    }
});

$('body').on('change', '#searchform-drive', function() {
    var $this = $(this);
    var mark = $('#searchform-mark').val();
    var model = $('#searchform-model').val();
    var engine = $('#searchform-engine').val();
    var drive = $this.val();

    $.ajax({
        type   : 'POST',
        url    : '/ajax/request/search',
        data   : {
            'mark': mark,
            'model': model,
            'engine': engine,
            'drive': drive
        },
        success: function(data) {
            $('#catalog-list').html(data);
        }
    });
});

$('body').on('change', '#searchform-engine', function() {
    var $this = $(this);
    var mark = $('#searchform-mark').val();
    var model = $('#searchform-model').val();
    var engine = $this.val();
    var drive = $('#searchform-drive').val();

    $.ajax({
        type   : 'POST',
        url    : '/ajax/request/search',
        data   : {
            'mark': mark,
            'model': model,
            'engine': engine,
            'drive': drive
        },
        success: function(data) {
            $('#catalog-list').html(data);
        }
    });
});
