<?php

function url($path='') {
  return trim(BASE_URL, '/') . '/' . trim($path, '/');
}

function redirect($path='') {
  header('Location: ' . url($path));
  exit;
}

function asset($path='') {
  return url('assets/' . trim($path, '/'));
}

function media($path='') {
  return url('uploads/' . trim($path, '/'));
}
