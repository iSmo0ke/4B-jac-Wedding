$("#email").change(function(){
    $(".alert").remove();
    
    var email = $(this).val();
    //console.log("Tu Email es: ", email)
    var datos= new FormData();
    datos.append("validarEmail", email);

    $.ajax({
        url: "ajax/formularios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(respuesta){
            //console.log("Contenido de respuesta: ", respuesta);
            if(respuesta){
                $("#email").val("");
                $("#email").parent().after(`
                    <div class="alert alert-warning">
                       <b>ERROR</b> 
                       El correo ya existe, ingrese otro diferente
                    </div>

                `);
            }
        }

    });
})