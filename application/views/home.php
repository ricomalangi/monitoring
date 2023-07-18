<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </div>

  <!-- Content Row -->
  <div class="row">
    <!-- server up -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Server Up</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $data['total_running'] ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- server down -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Server down</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $data['total_stopped'] ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- total nodes -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Server</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $data['total_node'] ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-server fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-6 col-md-6">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-success">List server up</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="dataTable">
              <thead>
                <tr>
                  <td>No</td>
                  <td>Name</td>
                  <td>VMID</td>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1;
                foreach ($data['nodes_up'] as $item) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['vmid'] ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">List server down</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="dataTable">
              <thead>
                <tr>
                  <td>No</td>
                  <td>Name</td>
                  <td>VMID</td>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1;
                foreach ($data['nodes_down'] as $item) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['vmid'] ?></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->