
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Server Information VMID <?= $vmid ?></h1>
  </div>
  <div class="row">
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-body">
          <table class="table table-bordered">
            <tr>
              <th style="width: 40%;">Hostname</th>
              <td><?= $data['hostname'] ?></td>
            </tr>
            <tr>
              <th>Number of cores</th>
              <td><?= $data['cores'] ?></td>
            </tr>
            <tr>
              <th>Ostype</th>
              <td><?= $data['ostype'] ?></td>
            </tr>
            <tr>
              <th>Architecture</th>
              <td><?= $data['arch'] ?></td>
            </tr>
            <tr>
              <th>Memory</th>
              <td><?= $data['memory'] ?> Mb</td>
            </tr>
            <tr>
              <th>Swap memory</th>
              <td><?= $data['swap'] ?> Mb</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-12 mt-4">
      <div class="card shadow">
        <div class="card-body">
          <div id="chart_cpu">

          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-12 mt-4">
      <div class="card shadow">
        <div class="card-body">
          <div id="chart_memory">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    var options_cpu = {
      chart: {
        height: 350,
        type: 'area'
      },
      stroke: {
        curve: 'smooth'
      },
      dataLabels: {
        enabled: false
      },
      series: [],
      title: {
        text: 'CPU Usage',
        align: 'left',
        margin: 10,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
          fontSize: '14px',
          fontWeight: 'bold',
          color: '#263238'
        },
      },
      xaxis: {
        type: 'datetime',
        labels: {
          formatter: function (value) {
            let formatDate = new Date(value)
            return formatDate.toLocaleDateString() + " " + formatDate.toLocaleTimeString(); // The formatter function overrides format property
          }, 
        },
      },
      yaxis: {
        labels: {
          formatter: function(value){
            return value + ' %'
          }
        }
      },
      noData: {
        text: 'Loading...'
      }
    }
    var chart_cpu = new ApexCharts(
      document.querySelector("#chart_cpu"),
      options_cpu
    );
    chart_cpu.render();
    var url_cpu = 'http://monitoring.test/server/graph_cpu/<?= $vmid ?>';
    $.getJSON(url_cpu, function(response) {
      chart_cpu.updateSeries([{
        name: 'CPU Usage',
        data: response
      }])
    });
    // ---------------------------------------
    var options_memory = {
      chart: {
        height: 350,
        type: 'area'
      },
      stroke: {
        curve: 'smooth'
      },
      dataLabels: {
        enabled: false
      },
      series: [],
      title: {
        text: 'Memory Usage',
        align: 'left',
        margin: 10,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
          fontSize: '14px',
          fontWeight: 'bold',
          color: '#263238'
        },
      },
      xaxis: {
        type: 'datetime',
        labels: {
          formatter: function (value) {
            let formatDate = new Date(value)
            return formatDate.toLocaleDateString() + " " + formatDate.toLocaleTimeString(); // The formatter function overrides format property
          }, 
        },
      },
      yaxis: {
        labels: {
          formatter: function(value){
            let result = parseFloat(value/1024/1024)
            return result.toFixed(4) + ' Mb'
          }
        }
      },
      noData: {
        text: 'Loading...'
      }
    }
    var chart_memory = new ApexCharts(document.querySelector("#chart_memory"), options_memory);
    chart_memory.render();
    var url_memory = 'http://monitoring.test/server/graph_memory/<?= $vmid ?>';
    $.getJSON(url_memory, function(response) {
      chart_memory.updateSeries([{
        name: 'CPU Usage',
        data: response
      }])
    });

  })
</script>
<!-- /.container-fluid -->