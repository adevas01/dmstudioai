<?php
// DM Studio AI - Authentication Helpers

function requireLogin()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php?route=login&nav=dmstudioai");
        exit;
    }
}

function requireRoles($allowedRoles)
{
    requireLogin();

    if (!in_array($_SESSION["role"], $allowedRoles)) {
        die("Access denied. You do not have permission to view this page.");
    }
}

function canManageUser($currentRole, $targetRole)
{
    if ($currentRole === "owner") {
        return in_array($targetRole, ["student", "teacher", "manager"]);
    }

    if ($currentRole === "manager") {
        return in_array($targetRole, ["student", "teacher"]);
    }

    if ($currentRole === "teacher") {
        return $targetRole === "student";
    }

    return false;
}