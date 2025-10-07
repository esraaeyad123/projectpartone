<header class="main-header">

    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <button id="darkModeToggle" onclick="toggleDarkMode()">
            <i class="fas fa-circle-half-stroke"></i>
        </button>

        <div>
            <button id="notificationsButton" onclick="showNotifications()" style="background-color: #ffe0c2; color: #f6902d; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                <i class="fas fa-bell"></i>
            </button>
            <button onclick="setLanguage('en')">
                <i class="fas fa-language"></i> English
            </button>
            <button onclick="setLanguage('ar')">
                <i class="fas fa-globe"></i> العربية
            </button>
        </div>
    </div>
    <h1>
        <i class="fas fa-microscope"></i>
        <span data-key="LIMS Control Panel">LIMS Control Panel</span>
    </h1>
</header>