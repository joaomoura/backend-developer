<?php echo view('parts/header'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Registro <small>de Clientes</small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active"><?= (isset($client) ? 'Editar' : 'Novo') ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Adicionar Cliente</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <?php if(!empty($client)) : 
                      $act = '/update/'.$client->id;
                    else :
                      $act = '/add';
              ?><?php endif; ?>
              <form id="quickForm" action="<?= $act ?>" method="post" enctype="multipart/form-data" novalidate="novalidate"> 
                <div class="card-body">
                  
                  <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php if(is_array(session()->getFlashdata('error'))) : ?>
                          <?php foreach(session()->getFlashdata('error') as $key => $value) : ?>
                              <i class="icon fas fa-ban"></i> <?= (is_string($key) ? $key : 'Error'); ?>!
                              <?= (is_string($value) ? $value : ' Operação não realizada.'); ?>
                          <?php endforeach;  ?>
                      <?php endif; ?>
                    </div>
                  <?php endif;?>
                  <?php if (session()->getFlashdata('success')): ?>
                      <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php if(is_array(session()->getFlashdata('success'))) : ?>
                            <?php foreach(session()->getFlashdata('success') as $key => $value) : ?>
                                <i class="icon fas fa-check"></i> <?= (is_string($key) ? $key : 'Success!'); ?>!
                                <?= (is_string($value) ? $value : ' Operação Realizada.'); ?>
                            <?php endforeach;  ?>
                        <?php endif; ?>
                      </div>
                  <?php endif;?>

                  <?php if(isset($client)) : ?>
                    <input type="hidden" name="id" value="<?= (!empty($client->id) ? $client->id : '') ?>">
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="nome" value="<?= (!empty($client->nome) ? $client->nome : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>Nome da Mãe</label>
                        <input type="text" name="mae" value="<?= (!empty($client->mae) ? $client->mae : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>Data de Nascimento</label>
                          <div class="input-group date" id="dtnascimento" data-target-input="nearest">
                              <input type="text" name="nascimento" value="<?= (!empty($client->nascimento) ? date_format(date_create($client->nascimento),"d/m/Y"): '') ?>"
                                class="form-control datetimepicker-input" data-target="#dtnascimento"/>
                              <div class="input-group-append" data-target="#dtnascimento" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">E-mail</label>
                        <input type="email" name="email" value="<?= (!empty($client->email) ? $client->email : '') ?>"  
                          class="form-control" id="exampleInputEmail1" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>CPF</label>
                        <input type="text" name="cpf" value="<?= (!empty($client->cpf) ? $client->cpf : '') ?>" class="form-control" data-inputmask="&quot;mask&quot;: &quot; 999.999.999–99 &quot;" data-mask="" inputmode="text">
                      </div>
                      <div class="form-group">
                        <label>CNS</label>
                        <input type="text" name="cns" value="<?= (!empty($client->cns) ? $client->cns : '') ?>"  
                          class="form-control" data-inputmask="&quot;mask&quot;: &quot; 999.9999.9999.9999 &quot;" data-mask="" inputmode="text" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" id="cep" value="<?= (!empty($client->cep) ? $client->cep : '') ?>" 
                          class="form-control" data-inputmask="&quot;mask&quot;: &quot; 99 999 – 999 &quot;" data-mask="" inputmode="text" placeholder="Enter ...">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label>Estado</label>
                            <input type="text" name="estado" id="estado" value="<?= (!empty($client->estado) ? $client->estado : '') ?>" 
                              class="form-control" placeholder="Enter ...">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>UF</label>
                            <input type="text" name="uf" id="uf" value="<?= (!empty($client->uf) ? $client->uf : '') ?>" 
                              class="form-control" placeholder="Enter ...">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" id="cidade" value="<?= (!empty($client->cidade) ? $client->cidade : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" name="bairro" id="bairro" value="<?= (!empty($client->bairro) ? $client->bairro : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>Logradouro</label>
                        <input type="text" name="logradouro" id="logradouro" value="<?= (!empty($client->logradouro) ? $client->logradouro : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label>Número</label>
                        <input type="number" name="numero" value="<?= (!empty($client->numero) ? $client->numero : '') ?>" 
                          class="form-control" placeholder="Enter ...">
                      </div>
                      <div class="form-group">
                        <label for="foto">Select a file:</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" id="foto" name="foto" value="<?= (!empty($client->foto) ? $client->foto : '') ?>"><br><br>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

<?php echo view('parts/footer'); ?>