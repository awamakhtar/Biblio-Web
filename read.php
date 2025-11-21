<?php
session_start();
require_once 'php/config.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id_lecteur'])) {
    header('Location: login.php');
    exit();
}

// Récupérer l'ID du livre
$id_livre = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id_livre == 0) {
    header('Location: index.php');
    exit();
}

// Récupérer les infos du livre
$sql = "SELECT * FROM livres WHERE id = $id_livre";
$result = mysqli_query($connexion, $sql);

if(mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$livre = mysqli_fetch_assoc($result);

// Vérifier si le livre a un fichier PDF
if(empty($livre['fichier_pdf'])) {
    $error = "Ce livre n'est pas disponible en lecture en ligne.";
}

// Chemin du fichier PDF
$pdf_path = 'books/' . $livre['fichier_pdf'];

// Vérifier si le fichier existe
if(!file_exists($pdf_path)) {
    $error = "Le fichier PDF est introuvable.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Lecture : <?php echo htmlspecialchars($livre['titre']); ?> - Biblio Web</title>
</head>
<body class="reading-mode">
    <?php include 'includes/navbar.php'; ?>

    <?php if(isset($error)): ?>
        <!-- Message d'erreur -->
        <div class="reader-error">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <h2>Oups !</h2>
            <p><?php echo $error; ?></p>
            <a href="details.php?id=<?php echo $id_livre; ?>" class="btn-back-error">
                <i class="fa-solid fa-arrow-left"></i>
                Retour aux détails
            </a>
        </div>
    <?php else: ?>
        <!-- Barre d'outils du lecteur -->
        <div class="reader-toolbar">
            <div class="toolbar-left">
                <a href="details.php?id=<?php echo $id_livre; ?>" class="btn-toolbar">
                    <i class="fa-solid fa-arrow-left"></i>
                    Retour
                </a>
                <span class="book-title-toolbar">
                    <?php echo htmlspecialchars($livre['titre']); ?>
                </span>
            </div>
            
            <div class="toolbar-center">
                <button class="btn-toolbar" onclick="previousPage()" title="Page précédente">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                
                <span class="page-info">
                    Page <span id="currentPage">1</span> / <span id="totalPages">--</span>
                </span>
                
                <button class="btn-toolbar" onclick="nextPage()" title="Page suivante">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="toolbar-right">
                <button class="btn-toolbar" onclick="zoomOut()" title="Zoom -">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </button>
                
                <button class="btn-toolbar" onclick="zoomIn()" title="Zoom +">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
                
                <button class="btn-toolbar" onclick="toggleFullscreen()" title="Plein écran">
                    <i class="fa-solid fa-expand"></i>
                </button>
                
                <button class="btn-toolbar" onclick="downloadPDF()" title="Télécharger">
                    <i class="fa-solid fa-download"></i>
                </button>
            </div>
        </div>

        <!-- Lecteur PDF -->
        <div class="pdf-reader-container" id="readerContainer">
            <canvas id="pdfCanvas"></canvas>
            
            <!-- Loading -->
            <div class="pdf-loading" id="pdfLoading">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <p>Chargement du livre...</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Inclure PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <script>
        <?php if(!isset($error)): ?>
        // Configuration PDF.js
        const pdfUrl = '<?php echo $pdf_path; ?>';
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.5;
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');

        // Charger le PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            pdfDoc = pdf;
            document.getElementById('totalPages').textContent = pdf.numPages;
            document.getElementById('pdfLoading').style.display = 'none';
            renderPage(pageNum);
        }).catch(function(error) {
            console.error('Erreur de chargement du PDF:', error);
            document.getElementById('pdfLoading').innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i><p>Erreur de chargement du PDF</p>';
        });

        // Rendre une page
        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('currentPage').textContent = num;
        }

        // File d'attente de rendu
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // Page précédente
        function previousPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        // Page suivante
        function nextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        // Zoom +
        function zoomIn() {
            scale += 0.2;
            queueRenderPage(pageNum);
        }

        // Zoom -
        function zoomOut() {
            if (scale <= 0.5) return;
            scale -= 0.2;
            queueRenderPage(pageNum);
        }

        // Plein écran
        function toggleFullscreen() {
            const container = document.getElementById('readerContainer');
            if (!document.fullscreenElement) {
                container.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Télécharger le PDF
        function downloadPDF() {
            const link = document.createElement('a');
            link.href = pdfUrl;
            link.download = '<?php echo $livre['titre']; ?>.pdf';
            link.click();
        }

        // Navigation au clavier
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') previousPage();
            if (e.key === 'ArrowRight') nextPage();
            if (e.key === 'Escape' && document.fullscreenElement) document.exitFullscreen();
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
mysqli_close($connexion);
?>