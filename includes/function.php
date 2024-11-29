<?php
session_start();
require 'db_con.php';




function showPassword()
{
    return '
    <script>
        document.querySelectorAll("[id^=showPassword]").forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                const passwordInput = this.closest(".row").querySelector("input[type=\'password\'], input[type=\'text\']");
                if (this.checked) {
                    passwordInput.type = "text";
                } else {
                    passwordInput.type = "password";
                }
            });
        });
    </script>
    ';
}
