// SCHOOL PRO LOGIN
document.addEventListener("DOMContentLoaded", function () {
    let generatedOTP = null;

    // Send OTP
    document.getElementById("sendOtpBtn")?.addEventListener("click", async function () {
        const mob = document.getElementById("mobno").value.trim();
        if (mob.length !== 10 || isNaN(mob)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }

        generatedOTP = Math.floor(100000 + Math.random() * 900000);
        alert("Your OTP is: " + generatedOTP);
        document.getElementById("hideotp").style.display = "block";

        const msg = encodeURIComponent(
            `Dear user, Your OTP to login SchoolPro is ${generatedOTP}. Shri Siddhi Vinayak Shikshan Sansthan`
        );
        const apiUrl = `https://alerts.prioritysms.com/api/v4/?api_key=A085b04763d2e186ca1f640d414241485&method=sms&message=${msg}&to=91${mob}&sender=SIDHIS`;

        try {
            const res = await fetch(apiUrl, { method: "POST" });
            const text = await res.text();
            if (text.toLowerCase().includes("success")) alert("OTP sent successfully!");
            else alert("Failed to send OTP. Response: " + text);
        } catch (err) {
            console.error(err);
            alert("Error sending OTP: " + err.message);
        }
    });

    // Validate OTP and login
    document.getElementById("loginForm")?.addEventListener("submit", function (e) {
        const entered = document.getElementById("otp").value;
        if (!generatedOTP) {
            alert("Please request an OTP first.");
            e.preventDefault();
            return;
        }
        if (entered != generatedOTP) {
            alert("Invalid OTP! Please try again.");
            e.preventDefault();
        } else {
            // on success, go to dashboard
            loadTab("dashboard");
        }
    });
});
