<?php
function addContentSecurityPolicy()
{
  // Apply CSP headers for all other requests
  header('Content-Security-Policy: default-src \'self\';');
}

