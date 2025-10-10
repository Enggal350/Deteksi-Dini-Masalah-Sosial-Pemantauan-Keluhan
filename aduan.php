<?php
header('Content-Type: application/json; charset=utf-8');
$jsonFile = __DIR__ . '/hasil_aduan.json';
$action = $_POST['action'] ?? '';
if (!in_array($action,['update','create'])) {
  echo json_encode(['success'=>false,'message'=>'Action tidak valid']); exit;
}

$data = [];
if (file_exists($jsonFile)) {
  $raw = file_get_contents($jsonFile);
  $data = json_decode($raw, true);
  if (!is_array($data)) $data = [];
}

// update
if ($action==='update') {
  $i = (int)$_POST['index'];
  $text = trim($_POST['text'] ?? '');
  if (!isset($data[$i])) { echo json_encode(['success'=>false,'message'=>'Index tidak ditemukan']); exit; }
  $data[$i]['text'] = $text;
  $data[$i]['tanggal'] = date('Y-m-d H:i:s');
}
// create
elseif ($action==='create') {
  $nama = $_POST['nama'] ?: 'Anonim';
  $kategori = $_POST['kategori'] ?: 'Lainnya';
  $text = $_POST['text'] ?: '';
  $data[] = [
    'aduan'=>true,
    'kategori'=>$kategori,
    'text'=>$text,
    'nama'=>$nama,
    'tanggal'=>date('Y-m-d H:i:s')
  ];
}

file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
echo json_encode(['success'=>true]);
