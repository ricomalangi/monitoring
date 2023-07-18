<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">List server</h1>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow">
                <div class="card-body">
                    <?php $this->load->view('templates/v_alert') ?>
                    <div class="table-responsive" id="table-holder">
                        <table class="table" id="dataTable">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Name</td>
                                    <td>VMID</td>
                                    <td>Max memory</td>
                                    <td>Status</td>
                                    <td>Uptime</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1;
                                foreach ($data as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item['name'] ?></td>
                                        <td><?= $item['vmid'] ?></td>
                                        <td><?= $item['maxmem'] ?> Mb</td>
                                        <td><?= ($item['status'] == 'running' ? '<span class="badge badge-success">running</span>' : '<span class="badge badge-danger">stopped</span>') ?></td>
                                        <td><?= $item['uptime'] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-circle btn-sm" data-toggle="modal" data-target="#poweron-<?= $item['vmid'] ?>" <?= $item['status'] == 'running' ? 'disabled' : '' ?>>
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                            <a href="<?= base_url("server/detail/") . $item['vmid'] ?>" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-warning btn-circle btn-sm" data-toggle="modal" data-target="#reboot-<?= $item['vmid'] ?>" <?= $item['status'] == 'stopped' ? 'disabled' : '' ?>>
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#poweroff-<?= $item['vmid'] ?>" <?= $item['status'] == 'stopped' ? 'disabled' : '' ?>>
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="poweron-<?= $item['vmid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-center p-2">Are you sure want to power on <b>Server VMID <?= $item['vmid'] ?></b>?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                    <a class="btn btn-primary" href="<?= base_url("server/poweron/") . $item['vmid'] ?>">Poweron</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="reboot-<?= $item['vmid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-center p-2">Are you sure want to reboot <b>Server VMID <?= $item['vmid'] ?></b>?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                    <a class="btn btn-primary" href="<?= base_url("server/stop/") . $item['vmid'] ?>">Reboot</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="poweroff-<?= $item['vmid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-center p-2">Are you sure want to shutdown <b>Server VMID <?= $item['vmid'] ?></b>?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="<?= base_url('server/shutdown/') . $item['vmid'] ?>" method="POST">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-primary" type="submit">Poweroff</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<!-- <table class="table" id="dataTable">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Name</td>
                                    <td>VMID</td>
                                    <td>Max memory</td>
                                    <td>Status</td>
                                    <td>Uptime</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1;
                                foreach ($data as $item) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item['name'] ?></td>
                                        <td><?= $item['vmid'] ?></td>
                                        <td><?= $item['maxmem'] ?> Mb</td>
                                        <td><?= ($item['status'] == 'running' ? '<span class="badge badge-success">running</span>' : '<span class="badge badge-danger">stopped</span>') ?></td>
                                        <td><?= $item['uptime'] ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url("server/detail/") . $item['vmid'] ?>" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-warning btn-circle btn-sm" data-toggle="modal" data-target="#reboot-<?= $item['vmid'] ?>" <?= $item['status'] == 'stopped' ? 'disabled' : '' ?>>
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#poweroff-<?= $item['vmid'] ?>" <?= $item['status'] == 'stopped' ? 'disabled' : '' ?>>
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="reboot-<?= $item['vmid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-center p-2">Are you sure want to reboot <b>Server VMID <?= $item['vmid'] ?></b>?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                    <a class="btn btn-primary" href="<?= base_url("server/stop/") . $item['vmid'] ?>">Reboot</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="poweroff-<?= $item['vmid'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h5 class="text-center p-2">Are you sure want to shutdown <b>Server VMID <?= $item['vmid'] ?></b>?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="<?= base_url('server/shutdown/') . $item['vmid'] ?>" method="POST">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-primary" type="submit">Poweroff</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </tbody>
                        </table> -->