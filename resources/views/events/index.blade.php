@extends('layouts.app')
@section('scripts')
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<!-- Bootstrap -->


<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js' defer></script>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            Plugin: ['dayGrid', 'interaction', 'timeGrid', 'list'],
            headerToolbar: {
                // left: 'prev,next today miBoton',
                left: 'prev,next today miBoton otroB',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            customButtons: {
                miBoton: {
                    text: "Spanish",
                    click: function() {


                        calendar.setOption('locale', 'ES');

                        //console.log(calendar.getOption('locale'));

                        document.querySelector('#lblTitulo').innerText = 'Título:';
                        document.querySelector('#lblHora').innerText = 'Hora:';
                        document.querySelector('#lblFullName').innerText = 'Nombre completo:';
                        document.querySelector('#lblIdentificacion').innerText = 'No. Identificación:';
                        document.querySelector('#lblTipoProcedimiento').innerText = 'Tipo de procedimiento:';
                        document.querySelector('#btnAgregar').innerText = 'Agregar';
                        document.querySelector('#btnModificar').innerText = 'Modificar';
                        document.querySelector('#btnEliminar').innerText = 'Eliminar';
                        document.querySelector('#btnCerrar').innerText = 'Cerrar';


                        // if (calendar.getOption('locale')=='fr') {

                        //     calendar.setOption('locale', 'es');
                        //     console.log(calendar.getOption('locale'));
                        //     calendar.render;


                        // }
                        // else{
                        //     calendar.setOption('locale', 'US');
                        //     console.log(calendar.getOption('locale'));
                        //     calendar.render;

                        // }
                    }
                },
                otroB: {
                    text: "English",
                    click: function() {


                        calendar.setOption('locale');

                        //console.log(calendar.getOption('locale'));

                        document.querySelector('#lblTitulo').innerText = 'Title:';
                        document.querySelector('#lblHora').innerText = 'Hour:';
                        document.querySelector('#lblFullName').innerText = 'Full Name:';
                        document.querySelector('#lblIdentificacion').innerText = 'Identification:';
                        document.querySelector('#lblTipoProcedimiento').innerText = 'Type of procedure:';
                        document.querySelector('#btnAgregar').innerText = 'Add';
                        document.querySelector('#btnModificar').innerText = 'Modify';
                        document.querySelector('#btnEliminar').innerText = 'Remove';
                        document.querySelector('#btnCerrar').innerText = 'Close';


                        // if (calendar.getOption('locale')=='fr') {

                        //     calendar.setOption('locale', 'es');
                        //     console.log(calendar.getOption('locale'));
                        //     calendar.render;


                        // }
                        // else{
                        //     calendar.setOption('locale', 'US');
                        //     console.log(calendar.getOption('locale'));
                        //     calendar.render;

                        // }
                    }
                }
            },
            dateClick: function(info) {

                limpiarFormulario();

                $('#txtFecha').val(info.dateStr);
                $('#btnAgregar').prop('disabled', false);
                $('#btnModificar').prop('disabled', true);
                $('#btnEliminar').prop('disabled', true);
                $('#exampleModal').modal();

            },
            eventClick: function(info) {

                $('#btnAgregar').prop('disabled', true);
                $('#btnModificar').prop('disabled', false);
                $('#btnEliminar').prop('disabled', false);


                $('#txtId').val(info.event.id);
                $('#txtTitulo').val(info.event.title);

                mes = (info.event.start.getMonth() + 1);
                dia = (info.event.start.getDate());
                año = (info.event.start.getFullYear());

                hora = info.event.start.getHours();
                minutos = info.event.start.getMinutes();
                hora = (hora < 10) ? "0" + hora : hora;
                minutos = (minutos < 10) ? "0" + minutos : minutos;
                horario = (hora + ":" + minutos);

                mes = (mes < 10) ? "0" + mes : mes;
                dia = (dia < 10) ? "0" + dia : dia;

                $('#txtFecha').val(año + "-" + mes + "-" + dia);
                $('#txtHora').val(horario);

                $('#txtFullName').val(info.event.extendedProps.full_name);
                $('#txtIdentificacion').val(info.event.extendedProps.identification);
                $('#txtTipoProcedimiento').val(info.event.extendedProps.type_of_procedure);

                $('#exampleModal').modal('toggle');

            },


            events: "{{url('/events/show')}}"

        });
        //calendar.setOption('locale', 'ES')
        calendar.render();

        $('#btnAgregar').click(function() {
            objEvento = recolectarDatosGUI("POST");
            enviarInformacion('', objEvento);
        });

        $('#btnEliminar').click(function() {
            objEvento = recolectarDatosGUI("DELETE");
            enviarInformacion('/' + $('#txtId').val(), objEvento);
        });

        $('#btnModificar').click(function() {
            objEvento = recolectarDatosGUI("PATCH");
            enviarInformacion('/' + $('#txtId').val(), objEvento);
        });

        function recolectarDatosGUI(method) {
            nuevoEvento = {
                id: $('#txtId').val(),
                title: $('#txtTitulo').val(),
                full_name: $('#txtFullName').val(),
                identification: $('#txtIdentificacion').val(),
                type_of_procedure: $('#txtTipoProcedimiento').val(),
                start: $('#txtFecha').val() + " " + $('#txtHora').val(),
                end: $('#txtFecha').val() + " " + $('#txtHora').val(),
                '_token': $("meta[name='csrf-token']").attr("content"),
                '_method': method
            }
            return nuevoEvento;
        }

        function enviarInformacion(accion, objEvento) {
            // let route = "{{url('/events')}}";
            $.ajax({
                type: "POST",
                url: "{{url('/events')}}" + accion,
                data: objEvento,
                success: function(msg) {
                    console.log(msg);
                    $('#exampleModal').modal('toggle');
                    calendar.refetchEvents();
                },
                error: function() {
                    alert("Hay un error");
                }
            });

        }

        function limpiarFormulario() {

            $('#txtId').val("");
            $('#txtTitulo').val("");

            $('#txtFecha').val("");
            $('#txtHora').val("07:00");

            $('#txtFullName').val("");
            $('#txtIdentificacion').val("");
            $('#txtTipoProcedimiento').val("");

        }
    });
</script>

@endsection

@section('content')
<div class="row">
    <div class="col"></div>
    <div class="col-9">
        <div id="calendar"></div>
    </div>
    <div class="col"></div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-none">
                    id:
                    <input type="text" name="txtId" id="txtId">
                    Fecha:
                    <input type="text" name="txtFecha" id="txtFecha">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label name="lblTitulo" id="lblTitulo" for="">Title:</label>
                        <input type="text" class="form-control" name="txtTitulo" id="txtTitulo">
                    </div>
                    <div class="form-group col-md-4">
                        <label name="lblHora" id="lblHora" for="">Hour:</label>
                        <input type="time" min="05:00" max="23:59" step="600" class="form-control" name="txtHora" id="txtHora">
                    </div>
                    <div class="form-group col-md-8">
                        <label name="lblFullName" id="lblFullName" for="">Full Name:</label>
                        <input type="text" class="form-control" name="txtFullName" id="txtFullName">
                    </div>
                    <div class="form-group col-md-4">
                        <label name="lblIdentificacion" id="lblIdentificacion" for="">Identification:</label>
                        <input type="text" class="form-control" name="txtIdentificacion" id="txtIdentificacion">
                    </div>
                    <div class="form-group col-md-12">
                        <label name="lblTipoProcedimiento" id="lblTipoProcedimiento" for="">Type of procedure:</label>
                        <select class="form-control" name="txtTipoProcedimiento" id="txtTipoProcedimiento">
                            <option value="Pediatric dentistry">Pediatric dentistry</option>
                            <option value="Orthodontics">Orthodontics</option>
                            <option value="Periodontics">Periodontics</option>
                        </select>
                    </div>





                </div>


            </div>
            <div class="modal-footer">

                <button id="btnAgregar" class="btn btn-success">Add</button>
                <button id="btnModificar" class="btn btn-warning">Modify</button>
                <button id="btnEliminar" class="btn btn-danger">Remove</button>
                <button id="btnCerrar" class="btn btn-secondary" data-dismiss="modal">Close</button>


            </div>
        </div>
    </div>
</div>

@endsection