@extends('admin.layouts.app')

@section('content')

	<!-- Content Header (Page header) -->
  <section class="content-header">					
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
          
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  {{-- <section class="content">
    <!-- Default box -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-6">							
          <div class="small-box card">
            <div class="inner">
              <h3>150</h3>
              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-4 col-6">							
          <div class="small-box card">
            <div class="inner">
              <h3>50</h3>
              <p>Total Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-4 col-6">							
          <div class="small-box card">
            <div class="inner">
              <h3>$1000</h3>
              <p>Total Sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
          </div>
        </div>
      </div>
    </div>					
    <!-- /.card -->
  </section> --}}
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>150</h3>

              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>50</h3>

              <p>Total Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>$1000</h3>

              <p>Total Sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection

@section('customJs')
<script>
  console.log("Hello")
</script>
@endsection