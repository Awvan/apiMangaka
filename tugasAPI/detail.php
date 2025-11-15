<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    die("<div class='text-center mt-5'><h4>❌ No manga selected.</h4><a href='index.php' class='btn btn-primary mt-3'>Back to Home</a></div>");
}

$url = "https://kitsu.io/api/edge/manga/$id";
$response = @file_get_contents($url);
$data = $response ? json_decode($response) : null;

if (!$data || empty($data->data)) {
    die("<div class='text-center mt-5'><h4>⚠️ Manga not found.</h4><a href='index.php' class='btn btn-primary mt-3'>Back</a></div>");
}

$manga = $data->data;
$attr = $manga->attributes;

$title = $attr->titles->en_jp ?? $attr->canonicalTitle;
$synopsis = $attr->synopsis ?? 'No synopsis available.';
$status = ucfirst($attr->status);
$rating = $attr->averageRating ?? 'N/A';
$poster = $attr->posterImage->large ?? 'https://via.placeholder.com/400x600?text=No+Image';
$startDate = $attr->startDate ?? 'Unknown';
$endDate = $attr->endDate ?? 'Ongoing';
$chapterCount = $attr->chapterCount ?? '-';
$volumeCount = $attr->volumeCount ?? '-';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Manga Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <button onclick="history.back()" class="btn btn-secondary mb-4">&larr; Back</button>

        <div class="card shadow-lg">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?= $poster ?>" alt="<?= htmlspecialchars($title) ?>" class="img-fluid rounded-start">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h2 class="card-title mb-3"><?= htmlspecialchars($title) ?></h2>
                        <p><b>Status:</b> <?= $status ?></p>
                        <p><b>Rating:</b> <?= $rating ?></p>
                        <p><b>Chapters:</b> <?= $chapterCount ?> | <b>Volumes:</b> <?= $volumeCount ?></p>
                        <p><b>Start Date:</b> <?= $startDate ?> | <b>End Date:</b> <?= $endDate ?></p>
                        <hr>
                        <p><?= nl2br(htmlspecialchars($synopsis)) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>