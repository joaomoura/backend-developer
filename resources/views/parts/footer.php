
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<!-- Nested datatables -->
<script src="/plugins/nested-datatables/nested.tables.min.js"></script>
<!-- Page specific script -->
<?php if(!empty($specific_scripts)) : ?>
	<?= $specific_scripts ?>
<?php endif; ?>
<script>
<?php if(isset($sales)) : ?>
  var data = <?php echo $sales; ?>

  document.getElementById("jsonTextarea").innerHTML = JSON.stringify(data, undefined, 2);

  var dataInJson = [
      <?php 
        $sales = (array)json_decode($sales);

        foreach($sales['sales'] as $key => $sale) : ?>
        {
          "data" : {
            "<?php echo 'id' ?>" : "<?php echo $sale->id ?>",
            "<?php echo 'date' ?>" : "<?php echo $sale->date ?>",
            "<?php echo 'amount' ?>" : "<?php echo $sale->amount ?>",
            "<?php echo 'name' ?>" : "<?php echo $sale->customer->name ?>",
            "<?php echo 'street' ?>" : "<?php echo $sale->customer->address->street ?>",
            "<?php echo 'neighborhood' ?>" : "<?php echo $sale->customer->address->neighborhood ?>",
            "<?php echo 'city' ?>" : "<?php echo $sale->customer->address->city ?>",
            "<?php echo 'state' ?>" : "<?php echo $sale->customer->address->state ?>",
            "<?php echo 'postal_code' ?>" : "<?php echo $sale->customer->address->postal_code ?>",
          },
          "kids" : [
          <?php
          $i = 1; 
          foreach($sale->installments as $installment) : ?>
            {
              "data" : {
                  "installment" : "<?= $i ?>",
                  "amount" : "<?= $installment->amount ?>",
                  "date" : "<?= $installment->date ?>",
              },
              "kids": [

              ] 
            },
          <?php $i++; endforeach; ?>
          ],
        },
      <?php endforeach; ?>
    ]


  var settigns = {
          "iDisplayLength": 20,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": false,
          "bInfo": false,
       };

 var table = new nestedTables.TableHierarchy("nested-table", dataInJson, settigns);
 table.initializeTableHierarchy();
<?php endif; ?>
</script>
</body>
</html>