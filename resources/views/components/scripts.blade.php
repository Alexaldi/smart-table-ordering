<!-- JQUERY JS -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- SPARKLINE JS-->
<script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>

<!-- CHARTJS CHART JS-->
<script src="{{ asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/chart/utils.js') }}"></script>

<!-- PIETY CHART JS-->
<script src="{{ asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>

<!-- INTERNAL SELECT2 JS -->
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>

<!-- INTERNAL Data tables js-->
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

<!-- ECHART JS-->
<script src="{{ asset('assets/plugins/echarts/echarts.js') }}"></script>

<!-- SIDE-MENU JS-->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- SIDEBAR JS -->
<script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>

<!-- Perfect SCROLLBAR JS-->
<script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/p-scroll/pscroll.js') }}"></script>
<script src="{{ asset('assets/plugins/p-scroll/pscroll-1.js') }}"></script>

<!-- APEXCHART JS -->
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>

<!-- INDEX JS -->
<script src="{{ asset('assets/js/index1.js') }}"></script>

<!-- CUSTOM JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    const success = @json(session('success'));
    const status = @json(session('status'));
    const errorMsg = @json(session('error'));
    const firstError = @json($errors->first() ?? null);

    const message = success ?? status;

    if (message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: success,
            confirmButtonText: 'OK'
        });
    }

    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: errorMsg,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });

    } else if (firstError) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: firstError,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });
    }
})();

/* Delete Confirmation */
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const type = form.dataset.type || 'data ini';

            Swal.fire({
                title: `Are you sure you want to delete ${type}?`,
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

/* Fungsi Download QR Code sebagai Gambar PNG */
function printQR(tableName, containerId) {
    const container = document.getElementById(containerId);
    const svg = container.querySelector('svg');
    
    if (!svg) {
        alert('QR Code not found.');
        return;
    }

    const svgData = new XMLSerializer().serializeToString(svg);
    const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const URL = window.URL || window.webkitURL || window;
    const blobURL = URL.createObjectURL(svgBlob);
    
    const image = new Image();
    image.onload = function () {
        const padding = 40;
        const qrSize = 400;
        const textAreaHeight = 80;
        const canvasWidth = qrSize + (padding * 2);
        const canvasHeight = qrSize + (padding * 2) + textAreaHeight;

        const canvas = document.createElement('canvas');
        canvas.width = canvasWidth;
        canvas.height = canvasHeight;
        const ctx = canvas.getContext('2d');
        
        // Background putih
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // QR Code (dengan padding)
        ctx.drawImage(image, padding, padding, qrSize, qrSize);

        // Nama meja
        ctx.fillStyle = '#111111';
        ctx.font = 'bold 24px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(tableName, canvasWidth / 2, qrSize + padding + 35);

        // Teks scan
        ctx.fillStyle = '#555555';
        ctx.font = '16px Arial';
        ctx.fillText('Scan to order', canvasWidth / 2, qrSize + padding + 60);
        
        // Download
        const pngUrl = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        const cleanName = tableName.replace(/\s+/g, '_');
        
        downloadLink.href = pngUrl;
        downloadLink.download = `QR_${cleanName}.png`;
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    };
    
    image.src = blobURL;
}
</script>