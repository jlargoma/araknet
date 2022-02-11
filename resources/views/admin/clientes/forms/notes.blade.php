<h3 class="text-left">ANOTACIONES</h3>
<div class="row">
    <div class="col-md-8 col-xs-12 ">
        <?php
        if ($oNotes):
            foreach ($oNotes as $v):
                $dateTime = strtotime($v->created_at);
                ?>
                <div>
                  <div class="row">
                    <div class="col-md-8">
                      <?php 
                      if (isset($allUsers[$v->user_id])){
                        $personal = $allUsers[$v->user_id];
                        echo "<b>$personal->n</b> ($personal->rn)";
                      }
                      ?>
                      
                    </div>
                    <div class="col-md-4">
                      {{convertDateToShow_text(date('Y-m-d',$dateTime),true)}}
                      <button class="btn editNote" data-id="{{$v->id}}" data-note="{{$v->note}}" data-uid="{{$v->user_id}}">Editar</button>
                    </div>
                  </div>
                  <p>{{$v->note}}</p>
                </div>
        <hr>
                <?php
            endforeach;
        endif;
        ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <form  action="{{ url('/admin/cliente/notes') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="c_id" value="{{ $customer->id }}">
            <input type="hidden" name="id" id="noteID" value="">
            <div class="form-simple">
                <label for="name">Usuario</label>
                <select class="form-control" name="uid" id="user_note" required="">
                  <option value="">Personal</option>
                  @foreach($allUsers as $id=>$c)
                  <option value="{{$id}}" @if($id == $u_current) selected @endif>{{$c->n}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-simple">
                <label for="name">Nota</label>
                <textarea name="note" id="note" class="form-control" style="min-height: 50vh; border: 1px solid #cecece;padding: 9px;"></textarea>
            </div>
            <button class="btn btn-success" type="submit">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
            </button>
            <button class="btn btn-danger" id="delNote" type="button" style="display: none;">Borrar</button>
            <button class="btn" id="newNote" type="button"  style="display: none;">Nueva</button>
        </form>
        
    </div>
</div>
