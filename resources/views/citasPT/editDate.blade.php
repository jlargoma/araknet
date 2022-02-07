<div class="col-xs-12">
    <h2 class="text-center"><?php echo ($id > 0) ? 'Editar Cita' : 'Nueva Cita' ?></h2>
    <div class="row">
        <form action="{{ url('/admin/citas/create') }}" method="post" id="formEdit">
            @if($id>0)            			
            <input type="hidden" name="idDate" value="{{$id}}">
            @endif
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="date_type" value="pt">
            <div class="row">
                <div class="col-xs-12 col-md-4 push-20">
                  <label for="id_user" id="tit_user">Cliente
                  @if($id<1)
                  <span><input type="checkbox" id="is_group" name="is_group">Es un Grupo</span>
                  @endif
                  </label>
                    <select class="js-select2 form-control" id="id_user" name="id_user" style="width: 100%; cursor: pointer" data-placeholder="Seleccione usuario.."  >
                        <option></option>
                        <?php foreach ($users as $key => $user): ?>

                            <option value="<?php echo $user->id; ?>" <?php if (isset($id_user) && $id_user == $user->id) echo 'selected' ?>>
                                <?php echo $user->name; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-xs-12 col-md-4 push-20">
                    <label for="id_email">Email</label>
                    <input class="form-control" type="email" id="NC_email" name="email" placeholder="email" value="{{$email}}"/>
                </div>
                <div class="col-xs-12 col-md-4 push-20">
                    <label for="id_email">Teléfono</label>
                    <input class="form-control" type="text" id="NC_phone" name="phone" placeholder="Teléfono" value="{{$phone}}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5 col-md-2  push-20">
                    <label for="date">Fecha</label>
                    <input class="js-datepicker form-control" value="{{$date}}" type="text" id="date" name="date" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
                </div>
                <div class="col-xs-3 col-md-1 not-padding  push-20">
                    <label for="id_user">hora</label>
                    <select class="form-control" id="hour" name="hour" style="width: 100%;" data-placeholder="hora" required >
                        <?php for ($i = 8; $i <= 22; $i++) : ?>
                            <?php
                            if ($i < 10) {
                                $hour = "0" . $i;
                            } else {
                                $hour = $i;
                            }
                            ?>
                            <option value="<?php echo $hour ?>" <?php if ($time == $i) echo 'selected'; ?>>
                                <?php echo $hour ?>: 00
                            </option>
                        <?php endfor; ?>

                    </select>
                </div>
                <div class="col-xs-4 col-md-1  push-20">
                  <label for="date">Hora Exacta</label>
                  <input class="form-control" type="time" value="{{$customTime}}" type="text" id="customTime" name="customTime">
                </div>
                <div class="col-xs-6 col-md-2 push-20">
                    <label for="id_coach">Entrenador</label>
                    <select class="js-select2 form-control" id="id_coach" name="id_coach" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." >
                        <option></option>
                        <?php foreach ($coachs as $key => $coach): ?>
                            <option value="<?php echo $coach->id; ?>" <?php if (isset($id_coach) && $id_coach == $coach->id) echo 'selected' ?>>
                                <?php echo $coach->name; ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                </div>
                <div class="col-xs-6 col-md-4 push-20">
                    <label for="id_type_rate">Servicio</label>
                    <select class="form-control" id="id_rate" name="id_rate" style="width: 100%;" data-placeholder="Seleccione un servicio" required >
                      <option value="-1"></option>
                        <?php foreach ($services as $key => $service): ?>
                            <option value="<?php echo $service->id; ?>"  <?php if (isset($id_serv) && $id_serv == $service->id) echo 'selected' ?>>
                                <?php echo $service->name; ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-xs-6 col-md-2 mt-1">
                  @if($id>0 && $is_valora && !$charge)   
                   <button class="btn btn-lg btn-user btn-danger" type="button" data-idUser="{{$id_user}}">
                    No Cobrado
                  </button>
                  @endif
                </div>
            </div>
        </form>

        <div class=" col-xs-12 form-group push-20">
            <div class="col-xs-12 text-center">
                @if($id>0)   
                <button class="btn btn-lg btn-user" type="button" data-idUser="{{$id_user}}">
                    Ficha Usuario
                </button>
                @endif
                <button class="btn btn-lg btn-success sendForm" data-id="formEdit"  type="button" >
                    Guardar
                </button>
                @if($id>0)   
                <button class="btn btn-lg btn-danger btnDeleteCita" type="button">
                    Eliminar
                </button>
                <a href="/admin/citas/duplicar/{{$id}}" class="btn btn-lg btn-secondary">
                  Duplicar
                </a>
                @endif
            </div>
        </div>
        <hr/>
    </div>
</div>