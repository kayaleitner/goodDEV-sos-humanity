<?php
/**
 * How-to Admin Page with Tabs, Upload/Download, Example JSON
 * JSON is stored outside the webroot for security.
 */

if (!function_exists('render_how_to_page')) :

  add_action('admin_menu', function() {
    add_menu_page(
      __('How-to', 'flynt'),
      __('How-to', 'flynt'),
      'edit_posts',
      'how-to-links',
      'render_how_to_page',
      'dashicons-welcome-learn-more',
      20
    );
  });

  function render_how_to_page(): void {
    // Private folder outside webroot (two levels above ABSPATH)
    //    $private_dir = dirname(ABSPATH, 2) . '/private/how-to';
    $private_dir = WP_CONTENT_DIR . '/private/how-to';

    // Ensure folder exists and is writable
    if (!file_exists($private_dir)) {
      if (!wp_mkdir_p($private_dir, 0755)) {
        echo '<div class="notice notice-error"><p>Fehler: Privater Ordner konnte nicht erstellt werden: ' . esc_html($private_dir) . '</p></div>';
        return;
      }
    }
    if (!is_writable($private_dir)) {
      echo '<div class="notice notice-error"><p>Fehler: Privater Ordner ist nicht beschreibbar: ' . esc_html($private_dir) . '</p></div>';
      return;
    }

    $json_path_option = get_option('how_to_json_file_path');
    $current_tab = $_GET['tab'] ?? 'overview';

    // Tabs
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=how-to-links&tab=overview" class="nav-tab ' . ($current_tab === 'overview' ? 'nav-tab-active' : '') . '">Overview</a>';
    echo '<a href="?page=how-to-links&tab=upload" class="nav-tab ' . ($current_tab === 'upload' ? 'nav-tab-active' : '') . '">Upload/Download How-to JSON</a>';
    echo '</h2>';

    // === Upload Tab ===
    if ($current_tab === 'upload') {
      if (isset($_POST['how_to_json_nonce']) && wp_verify_nonce($_POST['how_to_json_nonce'], 'save_how_to_json')) {
        if (!empty($_FILES['how_to_json']['tmp_name'])) {
          $file = $_FILES['how_to_json'];
          $filetype = wp_check_filetype($file['name']);

          if ($filetype['ext'] !== 'json') {
            echo '<div class="notice notice-error"><p>Bitte nur JSON-Dateien hochladen.</p></div>';
          }
          else {
            // Zielpfad für die Datei (immer how-to.json)
            $target_file = $private_dir . '/how-to.json';

            // Alte Datei löschen, falls vorhanden
            if (file_exists($target_file)) {
              unlink($target_file);
            }

            // Neue Datei hochladen
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
              update_option('how_to_json_file_path', $target_file);
              echo '<div class="notice notice-success"><p>JSON erfolgreich hochgeladen und gespeichert als how-to.json.</p></div>';
            }
            else {
              echo '<div class="notice notice-error"><p>Upload fehlgeschlagen. Überprüfe Schreibrechte für: ' . esc_html($target_file) . '</p></div>';
            }
          }
        }
        else {
          echo '<div class="notice notice-error"><p>Keine Datei ausgewählt.</p></div>';
        }
      }

      echo '<div style="
    background: #fff8e1;
    border-left: 4px solid #f7b600;
    padding: 20px;
    margin: 20px 0;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
">';

      echo '<h2 style="margin-top:0;">Upload JSON</h2>';
      echo '<p>Lade hier die JSON-Datei hoch, um die How-to-Anleitungen zu aktualisieren. Achte darauf, dass die Datei den Aufbau unter "Beispiel-JSON" hat.</p>';

      echo '<form method="post" enctype="multipart/form-data" style="margin-top:10px;">';
      wp_nonce_field('save_how_to_json', 'how_to_json_nonce');
      echo '<p><input type="file" name="how_to_json" accept=".json" style="margin-bottom:10px;" /></p>';
      echo '<p><input type="submit" class="button button-primary" value="Hochladen & Speichern"></p>';
      echo '</form>';

      echo '</div>';

      // Download
      if ($json_path_option && file_exists($json_path_option)) {
        echo '<div style="
        background: #f1f7ff;
        border-left: 4px solid #0073aa;
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: 5px;
    ">';
        echo '<h3>How-to-Anleitungen bearbeiten!</h3>';
        echo '<p style="margin: 0 0 10px 0;">Um die How-to-Anleitungen zu bearbeiten, lade die JSON-Datei herunter, erweitere die Links und beachte dabei den Aufbau des JSON unter <strong>Beispiel-JSON</strong>.</p>';
        echo '<p style="margin: 0 0 10px 0;">Nach dem Bearbeiten kannst du die Datei über Upload JSON (siehe oben) wieder hochladen, damit die Änderungen wirksam werden.</p>';
        echo '<p style="margin:0;"><a class="button button-primary" href="' . esc_url(admin_url('admin-post.php?action=download_how_to_json')) . '">Download aktuelles JSON</a></p>';
        echo '</div>';
      }

      // Beispiel-JSON
      $example_json = '{
  "how_to_links": [
    {
      "link": "https://example.com",
      "title": "Beispiel Titel",
      "description": "Beispiel Beschreibung"
    },
    {
      "link": "https://example.com/link",
      "title": "Beispiel Titel",
      "description": "Beispiel Beschreibung"
    }
  ]
}';
      echo '<h3>Beispiel-JSON</h3>';
      echo '<pre style="background:#f5f5f5;padding:10px;border:1px solid #ddd;">' . esc_html($example_json) . '</pre>';
    }

    // === Overview Tab ===
    if ($current_tab === 'overview') {
      echo '<h1>How to WordPress</h1>';
      echo '<p>Hier findest du alle How-to-Anleitungen. Die JSON-Datei kann im Tab "Upload/Download How-to JSON" bearbeitet werden.</p>';

      if (!$json_path_option || !file_exists($json_path_option)) {
        echo '<div class="notice notice-warning"><p>Es ist noch kein JSON hochgeladen. Bitte im Tab „Upload JSON“ hochladen.</p></div>';
      }
      else {
        $json = file_get_contents($json_path_option);
        $data = json_decode($json, TRUE);

        if (!empty($data['how_to_links'])) {
          echo '<div class="how-to-grid">';
          foreach ($data['how_to_links'] as $item) {
            printf(
              '<div class="how-to-card">
                            <h2><a href="%s" target="_blank">%s</a></h2>
                            <p>%s</p>
                        </div>',
              esc_url($item['link']),
              esc_html($item['title']),
              esc_html($item['description'])
            );
          }
          echo '</div>';
        }
        else {
          echo '<div class="notice notice-warning"><p>Die JSON-Datei enthält keine How-to Links.</p></div>';
        }
      }
    }
  }

  // Admin CSS
  add_action('admin_head', function() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_how-to-links') {
      echo '<style>
            .how-to-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
            .how-to-card { background:#fff; border:1px solid #ddd; padding:15px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05); }
            .how-to-card h2 { margin-top:0; font-size:16px; }
            .how-to-card a { text-decoration:none; color:#0073aa; }
            .how-to-card a:hover { text-decoration:underline; }
        </style>';
    }
  });

  // Admin-only Download
  add_action('admin_post_download_how_to_json', function() {
    if (!current_user_can('edit_posts')) {
      wp_die('Keine Berechtigung.');
    }
    $json_path_option = get_option('how_to_json_file_path');
    if ($json_path_option && file_exists($json_path_option)) {
      header('Content-Type: application/json');
      header('Content-Disposition: attachment; filename="how-to.json"');
      readfile($json_path_option);
      exit;
    }
    wp_die('Keine Datei gefunden.');
  });

endif;