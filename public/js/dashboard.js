//Menu Responsive
function showMenu(){
    $('#menu').css('top', '0');
}
function hiddeMenu(){
    $('#menu').css('top', '-100vh');
}

//FORMS
//Alert
function hiddeAlert(){
    $('.alert').addClass("d-none");
}
//Carregar Imagem
function loadImg(e, idDisplay, idLabel) {
    var selectedFile = event.target.files[0];
    var reader = new FileReader();

    var imgtag = document.getElementById(idDisplay);
    imgtag.title = selectedFile.name;

    reader.onload = function(event) {
        imgtag.src = event.target.result;
    };

    reader.readAsDataURL(selectedFile);
    document.getElementById(idLabel).innerHTML = event.target.files[0].name;
}
//Carregar Imagem 2
function readURL(input, format, width, height) {
    var widthBack = $(".editor-container").width();  
    if (input.files && input.files[0]) { 
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image').attr('src', e.target.result);
            var resize = new Croppie($('#image')[0], {
                viewport: { width: width, height: height, type: format },
                boundary: { width: widthBack, height: 290 },
                showZoomer: true,
                enableOrientation: true
            });
            $('#tamanho').html('');
            $('#resetEditor').show();
            $('#aviso').removeClass('d-none');
            $( ".dash-spinner" ).hide();
            $('#cortar').removeAttr("disabled");
            $('#cortar').on('click', function() {
                try {
                    console.log(resize.get().points);
                    $("#points").val(resize.get().points);
                    resize.result('base64').then(function(dataImg) {
                        var data = [{ image: dataImg }, { name: 'myimgage.jpg' }];
                        $('#result').attr('src', dataImg);
                    });
                    $('#editorImagem').modal('hide')
                }
                catch(err) {
                    console.log('resize');
                }
            });
            $('.reset').on('click', function() {
                try {
                    $("#fotoInput").val('');
                    resize.destroy();
                    $('#image').attr('src', '');
                    $('#aviso').addClass('d-none');
                    $('#resetEditor').hide();
                    $('#inputEditor').show();
                    $( ".zoomRow" ).fadeTo(1, 0);
                    $('#cortar').prop("disabled", true);
                }
                catch(err) {
                    console.log('err.message djhfdjkhgdfhkb');
                }
            });
            $('.zoomIn').on('click', function() {
                try {
                    var novo = resize.get().zoom + 0.2;
                    resize.setZoom(novo);
                }
                catch(err) {
                    console.log('err.message djhfdjkhgdfhkb');
                }
            });
            $('.zoomOut').on('click', function() {
                try {
                    var novo = resize.get().zoom - 0.2;
                    resize.setZoom(novo);
                }
                catch(err){
                    console.log('error');
                }
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
} 

//Loaders
function progressBar(){
    $( ".dash-botoes" ).hide(); 
    $( ".dash-spinner" ).show(); 
    var timer;
    var i = 50;
    timer = setInterval(function(e){
        console.log('%: '+i);
        i= i+0.1;
        if(i < 90 ){
            $("#progresso").css('width', i+'%');
        }
    }, 300);
}
function spinner(){
    $( ".dash-botoes" ).hide(); 
    $( ".dash-spinner" ).show(); 
}
