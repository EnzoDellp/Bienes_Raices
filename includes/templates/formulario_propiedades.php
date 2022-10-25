<fieldset>
        <legend>Información General</legend>

        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo"name="titulo" placeholder="titulo propiedad" value="<?php echo s($propiedad->titulo) ?>" >

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" placeholder="precio propiedad"value="<?php echo s($propiedad->precio);?>" >

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        <label for="descripcion">Descrípcion</label>
        <textarea  id="descripcion" name="descripcion" ><?php echo s($propiedad->descripcion);?> </textarea>
    </fieldset>


    <fieldset>
        <legend>Información de la propiedad</legend>

        <label for="habitaciones">habitaciones:</label>
        <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo s($propiedad->habitaciones);?>" >

        <label for="wc">baños:</label>
        <input type="number" id="wc" name="wc" placeholder="Ej: 1" min="1" max="9" value="<?php echo s($propiedad->wc);?>" >

        <label for="estacionamiento">estacionamiento:</label>
        <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 2" min="1" max="9" value="<?php echo s($propiedad->estacionamiento);?>" >

    </fieldset>

    <fieldset>
                <!-- <legend>Vendedor</legend>
            <select name="vendedores_id">
            <option value="">--Selecione--</option>
            <?php while($vendedor=mysqli_fetch_assoc($res)) { ?>
                <option  <?php echo $vendedores_id===$vendedor['id'] ? 'selected':''; ?> value="1"><?php echo $vendedor['nombre']." ".$vendedor['apellido'];  ?></option>
            <?php }; ?>

            </select> -->
            </fieldset>  