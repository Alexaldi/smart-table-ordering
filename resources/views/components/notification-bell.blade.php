<div class="sto-notification" data-sto-notification data-user-role="{{ auth()->user()->role }}"
    data-index-url="{{ route('notifications.index') }}" data-read-all-url="{{ route('notifications.read-all') }}"
    data-base-url="{{ url('/notifications') }}">
    <button type="button" class="sto-notification-toggle" aria-label="Notifikasi">
        <i class="fe fe-bell"></i>
        <span class="sto-notification-badge" hidden>0</span>
    </button>

    <div class="sto-notification-panel" hidden>
        <div class="sto-notification-head">
            <strong>Notifikasi</strong>
            <button type="button" class="sto-notification-read-all">Tandai dibaca</button>
        </div>

        <div class="sto-notification-list"></div>
        <div class="sto-notification-empty">Belum ada notifikasi.</div>
    </div>
</div>

@once
    <style>
        .sto-notification {
            position: relative;
            flex: 0 0 auto;
        }

        .sto-notification-toggle {
            width: 36px;
            height: 36px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #f9fafb;
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
        }

        .sto-notification-toggle:hover,
        .sto-notification-toggle:focus {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        .sto-notification-badge {
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            border: 2px solid #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
            padding: 0 4px;
            position: absolute;
            top: -7px;
            right: -7px;
        }

        .sto-notification-panel {
            width: min(340px, calc(100vw - 32px));
            max-height: 420px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 18px 42px rgba(15, 23, 42, .16);
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            z-index: 1000;
        }

        .sto-notification-head {
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #f3f4f6;
            padding: 0 12px;
        }

        .sto-notification-head strong {
            color: #111827;
            font-size: 14px;
        }

        .sto-notification-read-all {
            border: 0;
            background: transparent;
            color: #2563eb;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 0;
        }

        .sto-notification-list {
            max-height: 316px;
            overflow-y: auto;
        }

        .sto-notification-item {
            width: 100%;
            border: 0;
            border-bottom: 1px solid #f3f4f6;
            background: #fff;
            display: grid;
            grid-template-columns: 8px minmax(0, 1fr);
            gap: 10px;
            padding: 12px;
            text-align: left;
            cursor: pointer;
        }

        .sto-notification-item:hover,
        .sto-notification-item:focus {
            background: #f9fafb;
        }

        .sto-notification-item.unread {
            background: #eff6ff;
        }

        .sto-notification-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #d1d5db;
            margin-top: 5px;
        }

        .sto-notification-item.unread .sto-notification-dot {
            background: #2563eb;
        }

        .sto-notification-copy {
            min-width: 0;
        }

        .sto-notification-copy strong {
            display: block;
            color: #111827;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.45;
        }

        .sto-notification-copy small {
            display: block;
            color: #6b7280;
            font-size: 11px;
            margin-top: 4px;
        }

        .sto-notification-empty {
            color: #6b7280;
            font-size: 13px;
            padding: 18px 12px;
            text-align: center;
        }

        .sto-sound-enable {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 1200;
            min-height: 40px;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            background: #eff6ff;
            color: #1d4ed8;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .18);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
        }

        .sto-sound-enable:hover,
        .sto-sound-enable:focus {
            background: #dbeafe;
            border-color: #93c5fd;
        }

        @media (max-width: 991.98px) {
            .sto-notification-panel {
                position: fixed;
                top: var(--notification-panel-top, 92px);
                left: auto;
                right: 16px;
                width: min(360px, calc(100vw - 32px));
                max-height: min(420px, calc(100vh - var(--notification-panel-top, 92px) - 16px));
            }
        }

        @media (max-width: 575.98px) {
            .sto-notification-panel {
                left: 12px;
                right: 12px;
                width: auto;
                max-height: min(420px, calc(100vh - var(--notification-panel-top, 92px) - 12px));
            }

            .sto-sound-enable {
                left: 12px;
                right: 12px;
                justify-content: center;
            }
        }
    </style>

    <script>
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            window.STONotificationSound = window.STONotificationSound || (function() {
                let context = null;
                let promptButton = null;

                function getContext() {
                    try {
                        const AudioContext = window.AudioContext || window.webkitAudioContext;

                        if (!AudioContext) {
                            return null;
                        }

                        if (!context) {
                            context = new AudioContext();
                        }

                        return context;
                    } catch (error) {
                        return null;
                    }
                }

                function hidePrompt() {
                    promptButton?.remove();
                    promptButton = null;
                }

                function showPrompt() {
                    if (promptButton) {
                        return;
                    }

                    promptButton = document.createElement('button');
                    promptButton.type = 'button';
                    promptButton.className = 'sto-sound-enable';
                    promptButton.innerHTML = '<i class="fe fe-volume-2"></i><span>Aktifkan suara notif</span>';

                    promptButton.addEventListener('click', async function() {
                        const enabled = await unlock();

                        if (enabled) {
                            await play(true);
                        }
                    });

                    document.body.appendChild(promptButton);
                }

                async function unlock() {
                    const audioContext = getContext();

                    if (!audioContext) {
                        return false;
                    }

                    try {
                        if (audioContext.state === 'suspended') {
                            await audioContext.resume();
                        }

                        if (audioContext.state === 'running') {
                            hidePrompt();
                            return true;
                        }
                    } catch (error) {
                        showPrompt();
                        return false;
                    }

                    showPrompt();
                    return false;
                }

                async function play(isTest = false) {
                    const audioContext = getContext();

                    if (!audioContext) {
                        return false;
                    }

                    const unlocked = await unlock();

                    if (!unlocked) {
                        return false;
                    }

                    try {
                        const startAt = audioContext.currentTime;
                        const oscillator = audioContext.createOscillator();
                        const gain = audioContext.createGain();

                        oscillator.type = 'sine';
                        oscillator.frequency.setValueAtTime(isTest ? 740 : 880, startAt);
                        oscillator.frequency.setValueAtTime(isTest ? 920 : 660, startAt + 0.12);

                        gain.gain.setValueAtTime(0.001, startAt);
                        gain.gain.exponentialRampToValueAtTime(0.18, startAt + 0.02);
                        gain.gain.exponentialRampToValueAtTime(0.001, startAt + 0.34);

                        oscillator.connect(gain);
                        gain.connect(audioContext.destination);

                        oscillator.start(startAt);
                        oscillator.stop(startAt + 0.36);

                        return true;
                    } catch (error) {
                        showPrompt();
                        return false;
                    }
                }

                ['pointerdown', 'touchstart', 'keydown', 'click'].forEach(function(eventName) {
                    document.addEventListener(eventName, function() {
                        unlock();
                    }, {
                        once: true,
                        passive: true,
                    });
                });

                return {
                    unlock,
                    play,
                    showPrompt,
                };
            })();

            function escapeText(value) {
                return String(value || '').replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;',
                    } [char];
                });
            }

            function dispatchNotificationToPage(notification) {
                if (!notification) {
                    return;
                }

                window.dispatchEvent(new CustomEvent('sto:notification-created', {
                    detail: notification,
                }));
            }

            function setupNotification(root) {
                const indexUrl = root.dataset.indexUrl;
                const readAllUrl = root.dataset.readAllUrl;
                const baseUrl = root.dataset.baseUrl;
                const userRole = root.dataset.userRole;

                const toggle = root.querySelector('.sto-notification-toggle');
                const panel = root.querySelector('.sto-notification-panel');
                const badge = root.querySelector('.sto-notification-badge');
                const list = root.querySelector('.sto-notification-list');
                const empty = root.querySelector('.sto-notification-empty');
                const readAll = root.querySelector('.sto-notification-read-all');

                const seenNotificationIds = new Set();
                let notificationsLoaded = false;

                function setBadge(count) {
                    const unread = Number(count || 0);

                    badge.textContent = unread > 99 ? '99+' : unread;
                    badge.hidden = unread === 0;
                }

                function renderNotifications(notifications) {
                    list.innerHTML = '';
                    empty.hidden = notifications.length > 0;

                    notifications.forEach(function(notification) {
                        const item = document.createElement('button');

                        item.type = 'button';
                        item.className = `sto-notification-item ${notification.is_read ? '' : 'unread'}`;
                        item.innerHTML = `
                        <span class="sto-notification-dot" aria-hidden="true"></span>
                        <span class="sto-notification-copy">
                            <strong>${escapeText(notification.message)}</strong>
                            <small>${escapeText(notification.created_at_human || '')}</small>
                        </span>
                    `;

                        item.addEventListener('click', async function() {
                            if (notification.is_read) {
                                return;
                            }

                            await fetch(`${baseUrl}/${notification.id}/read`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                            });

                            await loadNotifications();
                        });

                        list.appendChild(item);
                    });
                }

                async function unlockNotificationSound() {
                    await window.STONotificationSound.unlock();
                }

                async function playNotificationSound() {
                    await window.STONotificationSound.play();
                }

                function showNotificationToast(notifications) {
                    if (!notifications.length || !window.Swal) {
                        return;
                    }

                    const newest = notifications[0];
                    const moreCount = notifications.length - 1;

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: newest.message,
                        text: moreCount > 0 ? `+${moreCount} notifikasi baru lainnya` : '',
                        showConfirmButton: false,
                        timer: 4200,
                        timerProgressBar: true,
                        width: 360,
                    });
                }

                function shouldSurfaceNotification(notification) {
                    if (!notification) {
                        return false;
                    }

                    if (userRole === 'owner') {
                        return false;
                    }

                    if (userRole === 'admin') {
                        return [
                            'order_paid_cash',
                            'order_paid_midtrans',
                            'order_item_rejected',
                        ].includes(notification.type);
                    }

                    return true;
                }

                async function loadNotifications(options = {}) {
                    const shouldNotify = Boolean(options.shouldNotify);
                    const shouldDispatch = Boolean(options.shouldDispatch);

                    let response;

                    try {
                        response = await fetch(indexUrl, {
                            headers: {
                                'Accept': 'application/json',
                            },
                        });
                    } catch (error) {
                        return;
                    }

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();
                    const notifications = data.notifications || [];

                    const newUnread = notifications.filter(function(notification) {
                        return !notification.is_read && !seenNotificationIds.has(notification.id);
                    });

                    setBadge(data.unread_count);
                    renderNotifications(notifications);

                    const visibleUnread = newUnread.filter(shouldSurfaceNotification);

                    if (notificationsLoaded && visibleUnread.length > 0) {
                        if (shouldNotify) {
                            await playNotificationSound();
                            showNotificationToast(visibleUnread);
                        }

                        if (shouldDispatch) {
                            visibleUnread.forEach(dispatchNotificationToPage);
                        }
                    }

                    notifications.forEach(function(notification) {
                        seenNotificationIds.add(notification.id);
                    });

                    notificationsLoaded = true;
                }

                function subscribeRealtime() {
                    if (!window.Echo || !userRole) {
                        return false;
                    }

                    const channelName = `role.${userRole}`;

                    window.Echo.private(channelName)
                        .listen('.notification.created', function(event) {
                            const incoming = event.notification ?? event;

                            if (!incoming) {
                                return;
                            }

                            loadNotifications({
                                shouldNotify: false,
                                shouldDispatch: false,
                            });

                            if (!shouldSurfaceNotification(incoming)) {
                                return;
                            }

                            window.dispatchEvent(new CustomEvent('sto:notification-created', {
                                detail: incoming
                            }));

                            showNotificationToast([incoming]);
                            playNotificationSound().catch(function() {});
                        });

                    return true;
                }

                function subscribeRealtimeWithRetry(attempt = 1) {
                    if (subscribeRealtime()) {
                        return;
                    }

                    if (attempt >= 10) {
                        return;
                    }

                    window.setTimeout(function() {
                        subscribeRealtimeWithRetry(attempt + 1);
                    }, 1000);
                }

                toggle.addEventListener('click', async function() {
                    await unlockNotificationSound();

                    const rect = toggle.getBoundingClientRect();
                    const top = Math.max(12, Math.min(rect.bottom + 10, window.innerHeight - 120));

                    panel.style.setProperty('--notification-panel-top', `${top}px`);
                    panel.hidden = !panel.hidden;

                    if (!panel.hidden) {
                        await loadNotifications();
                    }
                });

                readAll.addEventListener('click', async function() {
                    await unlockNotificationSound();

                    await fetch(readAllUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    await loadNotifications();
                });

                document.addEventListener('click', function(event) {
                    if (!root.contains(event.target)) {
                        panel.hidden = true;
                    }
                });

                ['pointerdown', 'touchstart', 'keydown', 'click'].forEach(function(eventName) {
                    document.addEventListener(eventName, unlockNotificationSound, {
                        once: true,
                        passive: true,
                    });
                });

                loadNotifications().then(function() {
                    subscribeRealtimeWithRetry();
                });
            }

            document.querySelectorAll('[data-sto-notification]').forEach(setupNotification);
        })
        ();
    </script>
@endonce
