<?php 
    $json = file_get_contents('hasil_aduan.json');
    $data = json_decode($json, true)
?>

    <!-- 📋 Tabel Aduan -->
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-success">
          <tr>
          
            <th>Kategori</th>
            <th>Isi Aduan</th>
            
          </tr>
          <?php 
          foreach($data as $item) {
          ?>
          <tr>
            
            <th><?php echo $item ['kategori']?> </th>
            <th><?php echo $item ['text']?></th>
            
          </tr>
          <?php } ?>
        </thead>
        <tbody id="aduanTable"></tbody>
      </table>
    </div>
  </div>
