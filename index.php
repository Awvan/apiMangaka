<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manga Search | Kitsu API</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .truncate {
      display: -webkit-box;
      display: box;
      /* fallback untuk browser lain */
      -webkit-box-orient: vertical;
      box-orient: vertical;
      -webkit-line-clamp: 4;
      line-clamp: 4;
      /* versi standar modern */
      overflow: hidden;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="row mb-4">
      <div class="col-md-6 offset-md-3">
        <h1 class="text-center mb-3">🔍 Manga Search (Kitsu API)</h1>
        <form method="GET" class="d-flex shadow-sm">
          <input type="text" name="query" class="form-control" placeholder="Enter manga title (e.g., Naruto)"
            value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
          <button type="submit" name="action" value="search" class="btn btn-primary ms-2">Search</button>
          <button type="submit" name="action" value="random" class="btn btn-success ms-2">Random</button>
        </form>
      </div>
    </div>

    <div class="row g-4">
      <?php
      if (isset($_GET['action'])) {

        // Jika tombol RANDOM ditekan
        if ($_GET['action'] === 'random') {
          // Angka acak untuk offset data (Kitsu punya ribuan manga)
          $offset = rand(0, 10000);
          $url = "https://kitsu.io/api/edge/manga?page[limit]=1&page[offset]=$offset";
          $response = @file_get_contents($url);

          if ($response) {
            $data = json_decode($response);
            if (!empty($data->data)) {
              foreach ($data->data as $manga) {
                $title = $manga->attributes->titles->en_jp ?? $manga->attributes->canonicalTitle;
                $synopsis = $manga->attributes->synopsis ?? 'No description available.';
                $status = ucfirst($manga->attributes->status);
                $poster = $manga->attributes->posterImage->medium ?? 'https://via.placeholder.com/300x400?text=No+Image';
                $rating = $manga->attributes->averageRating ?? 'N/A';
                ?>
                <div class="col-md-3">
                  <div class="card h-100 shadow-sm">
                    <a href="detail.php?id=<?= $manga->id ?>">
                      <img src="<?= $poster ?>" class="card-img-top" alt="<?= htmlspecialchars($title) ?>">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title text-truncate">
                        <a href="detail.php?id=<?= $manga->id ?>" class="text-decoration-none text-dark">
                          <?= htmlspecialchars($title) ?>
                        </a>
                      </h5>
                      <p class="text-muted mb-1"><b>Status:</b> <?= $status ?></p>
                      <p class="text-muted mb-1"><b>Rating:</b> <?= $rating ?></p>
                      <p class="card-text small truncate"><?= htmlspecialchars($synopsis) ?></p>
                    </div>
                  </div>
                </div>

                <?php
              }
            } else {
              echo "<div class='alert alert-warning text-center'>No random manga found!</div>";
            }
          } else {
            echo "<div class='alert alert-danger text-center'>Failed to fetch random manga!</div>";
          }
        }

        // Jika tombol SEARCH ditekan
        elseif ($_GET['action'] === 'search' && !empty(trim($_GET['query']))) {
          $query = urlencode(trim($_GET['query']));
          $url = "https://kitsu.io/api/edge/manga?filter[text]=$query";
          $response = @file_get_contents($url);

          if ($response) {
            $data = json_decode($response);

            if (!empty($data->data)) {
              foreach ($data->data as $manga) {
                $title = $manga->attributes->titles->en_jp ?? $manga->attributes->canonicalTitle;
                $synopsis = $manga->attributes->synopsis ?? 'No description available.';
                $status = ucfirst($manga->attributes->status);
                $poster = $manga->attributes->posterImage->medium ?? 'https://via.placeholder.com/300x400?text=No+Image';
                $rating = $manga->attributes->averageRating ?? 'N/A';
                ?>
                <div class="col-md-3">
                  <div class="card h-100 shadow-sm">
                    <a href="detail.php?id=<?= $manga->id ?>">
                      <img src="<?= $poster ?>" class="card-img-top" alt="<?= htmlspecialchars($title) ?>">
                    </a>
                    <div class="card-body">
                      <h5 class="card-title text-truncate">
                        <a href="detail.php?id=<?= $manga->id ?>" class="text-decoration-none text-dark">
                          <?= htmlspecialchars($title) ?>
                        </a>
                      </h5>
                      <p class="text-muted mb-1"><b>Status:</b> <?= $status ?></p>
                      <p class="text-muted mb-1"><b>Rating:</b> <?= $rating ?></p>
                      <p class="card-text small truncate"><?= htmlspecialchars($synopsis) ?></p>
                    </div>
                  </div>
                </div>

                <?php
              }
            } else {
              echo "<div class='alert alert-warning text-center'>No manga found for '<b>" . htmlspecialchars($_GET['query']) . "</b>'</div>";
            }
          } else {
            echo "<div class='alert alert-danger text-center'>Failed to fetch data from Kitsu API!</div>";
          }
        } else {
          echo "<div class='alert alert-info text-center'>Please enter a manga title to search.</div>";
        }
      } else {
        echo "<div class='alert alert-info text-center'>Use the search bar or click 'Random' to discover manga!</div>";
      }
      ?>

    </div>
  </div>

</body>

</html>