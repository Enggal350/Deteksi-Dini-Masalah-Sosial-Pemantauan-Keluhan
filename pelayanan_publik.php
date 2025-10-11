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
          </thead>
          <?php 
          foreach($data as $item) {
            if ($item['kategori'] === 'Pelayanan Publik' ):
          ?>
          <tr>
          
            <th><?php echo $item ['kategori']?> </th>
            <th><?php echo $item ['text']?></th>
            
          </tr>
          <?php endif; ?>
          <?php } ?>
        <tbody id="aduanTable"></tbody>
      </table>
    </div>
  </div>
