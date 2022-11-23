<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "test";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("No se puede conectar a la base de datos");
}
$dni        = "";
$nombre       = "";
$direccion     = "";
$area   = "";
$descripcion   = "";
$exito    = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id         = $_GET['id'];
    $sql1       = "delete from empleados where id = '$id'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $exito= "Datos eliminados con éxito"; 
    }else{
        $error  = "Error al eliminar los datos"; 
    }
}
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from empleados where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $dni        = $r1['dni'];
    $nombre       = $r1['nombre'];
    $direccion     = $r1['direccion'];
    $area   = $r1['area'];
    $descripcion  = $r1['descripcion']; 

    if ($dni == '') {
        $error = "Datos no encontrados";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $dni        = $_POST['dni'];
    $nombre       = $_POST['nombre'];
    $direccion     = $_POST['direccion'];
    $area   = $_POST['area'];
    $descripcion   = $_POST['descripcion'];

    if ($dni && $nombre && $direccion && $area && $descripcion) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update empleados set dni = '$dni',nombre='$nombre',direccion = '$direccion',area='$area' ,descripcion='$descripcion' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $exito= "Datos actualizados correctamente";
            } else {
                $error  = "No se pudieron actualizar los datos";
            }
        } else { //untuk insert
            $sql1   = "insert into empleados(dni,nombre,direccion,area,descripcion) values ('$dni','$nombre','$direccion','$area','$descripcion')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $exito    = "Datos ingresados correctamente";
            } else {
                $error      = "Error al ingresar datos";
            }
        }
    } else {
        $error = "Por favor ingresa todos los datos";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Crear / Editar Datos de Empleados
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");//5 : detik
                }
                ?>
                <?php
                if ($exito) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $exito?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="dni" class="col-sm-2 col-form-label">DNI</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="dni" name="dni" value="<?php echo $dni ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="direccion" class="col-sm-2 col-form-label">Dirección</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="area" class="col-sm-2 col-form-label">Área</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="area" id="area">
                                <option value="">- Seleccionar -</option>
                                <option value="sistemas" <?php if ($area == "sistemas") echo "selected" ?>>sistemas</option>
                                <option value="finanzas" <?php if ($area == "finanzas") echo "selected" ?>>finanzas</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="direccion" class="col-sm-2 col-form-label">Descripción</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" value="<?php echo $descripcion ?>"> <?php echo $descripcion ?> </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="Aceptar" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Datos del Empleado
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">DNI</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Área</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from empleados order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $dni        = $r2['dni'];
                            $nombre       = $r2['nombre'];
                            $direccion     = $r2['direccion'];
                            $area   = $r2['area'];
                            $descripcion  = $r2['descripcion'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $dni ?></td>
                                <td scope="row"><?php echo $nombre ?></td>
                                <td scope="row"><?php echo $direccion ?></td>
                                <td scope="row"><?php echo $area ?></td>
                                <td scope="row"><?php echo $descripcion ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Editar</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Eliminar</button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</body>

    <script src="https://cdn.tiny.cloud/1/t2537jv6fs00yo0ry56l6ry7mb53wmv1bydm5ruxsslormaa/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
          selector: 'textarea#descripcion',
          plugins: 'image code',
          toolbar: 'undo redo | link image | code',
          /* enable title field in the Image dialog*/
          image_title: true,
          /* enable automatic uploads of images represented by blob or data URIs*/
          automatic_uploads: true,
          /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
          */
          file_picker_types: 'image',
          /* and here's our custom image picker*/
          file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.addEventListener('change', (e) => {
              const file = e.target.files[0];

              const reader = new FileReader();
              reader.addEventListener('load', () => {
                /*
                  Note: Now we need to register the blob in TinyMCEs image blob
                  registry. In the next release this part hopefully won't be
                  necessary, as we are looking to handle it internally.
                */
                const id = 'blobid' + (new Date()).getTime();
                const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                const base64 = reader.result.split(',')[1];
                const blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
              });
              reader.readAsDataURL(file);
            });

            input.click();
          },
          content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>

</html>
