<style>
/* Custom Confirm Modal Styles */
.custom-confirm-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    opacity: 0;
    transition: opacity 0.2s ease-out;
}
.custom-confirm-overlay.show {
    opacity: 1;
}
.custom-confirm-modal {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 400px;
    padding: 28px 24px;
    text-align: center;
    transform: scale(0.92);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid rgba(226, 232, 240, 0.8);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}
.custom-confirm-overlay.show .custom-confirm-modal {
    transform: scale(1);
}
.custom-confirm-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px auto;
}
.custom-confirm-icon.warning {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}
.custom-confirm-icon.danger {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}
.custom-confirm-icon.info {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.custom-confirm-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 10px;
    line-height: 1.3;
}
.custom-confirm-message {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 26px;
    padding: 0 8px;
}
.custom-confirm-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}
.custom-confirm-btn {
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.15s ease;
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    outline: none;
}
.custom-confirm-btn-cancel {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
}
.custom-confirm-btn-cancel:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.custom-confirm-btn-confirm {
    color: #ffffff;
}
.custom-confirm-btn-confirm.warning {
    background: #d97706;
    box-shadow: 0 4px 6px -1px rgba(217, 119, 6, 0.2);
}
.custom-confirm-btn-confirm.warning:hover {
    background: #b45309;
}
.custom-confirm-btn-confirm.danger {
    background: #dc2626;
    box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2);
}
.custom-confirm-btn-confirm.danger:hover {
    background: #b91c1c;
}
.custom-confirm-btn-confirm.info {
    background: #15803d;
    box-shadow: 0 4px 6px -1px rgba(21, 128, 61, 0.2);
}
.custom-confirm-btn-confirm.info:hover {
    background: #166534;
}
</style>

<script>
window.SwalConfirm = function(title, message, type = 'warning') {
    return new Promise((resolve) => {
        // Create elements
        const overlay = document.createElement('div');
        overlay.className = 'custom-confirm-overlay';
        
        let iconSvg = '';
        if (type === 'danger') {
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>`;
        } else if (type === 'info') {
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>`;
        } else { // warning
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>`;
        }

        overlay.innerHTML = `
            <div class="custom-confirm-modal">
                <div class="custom-confirm-icon ${type}">
                    ${iconSvg}
                </div>
                <div class="custom-confirm-title">${title}</div>
                <div class="custom-confirm-message">${message}</div>
                <div class="custom-confirm-buttons">
                    <button type="button" class="custom-confirm-btn custom-confirm-btn-cancel">Batal</button>
                    <button type="button" class="custom-confirm-btn custom-confirm-btn-confirm ${type}">Konfirmasi</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Force reflow and show
        setTimeout(() => overlay.classList.add('show'), 10);
        
        const cancelBtn = overlay.querySelector('.custom-confirm-btn-cancel');
        const confirmBtn = overlay.querySelector('.custom-confirm-btn-confirm');
        
        const close = (result) => {
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.remove();
                resolve(result);
            }, 200);
        };
        
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            close(false);
        });
        
        confirmBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            close(true);
        });
        
        // Close on escape key
        const escListener = (e) => {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', escListener);
                close(false);
            }
        };
        document.addEventListener('keydown', escListener);
    });
};

// Global event interceptor for data-confirm forms and links
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.hasAttribute('data-confirm')) {
            e.preventDefault();
            const message = form.getAttribute('data-confirm');
            const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
            const type = form.getAttribute('data-confirm-type') || 'warning';
            
            window.SwalConfirm(title, message, type).then((confirmed) => {
                if (confirmed) {
                    form.submit();
                }
            });
        }
    });

    document.addEventListener('click', function(e) {
        const anchor = e.target.closest('a[data-confirm]');
        if (anchor) {
            e.preventDefault();
            const message = anchor.getAttribute('data-confirm');
            const title = anchor.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
            const type = anchor.getAttribute('data-confirm-type') || 'warning';
            
            window.SwalConfirm(title, message, type).then((confirmed) => {
                if (confirmed) {
                    window.location.href = anchor.href;
                }
            });
        }
    });
});
</script>
