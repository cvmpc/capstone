<aside id="sidebar" class="sidebar">
  <?php if ($_SESSION['role'] === 'Admin') { ?>
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#member-list" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people"></i><span>Member</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="member-list" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="member.php">
              <i class="bi bi-circle"></i><span>Member List</span>
            </a>
          </li>
          <li>
            <a href="member-archived.php">
              <i class="bi bi-circle"></i><span>Archived</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#staff-list" data-bs-toggle="collapse" href="#">
          <i class="bi bi-person"></i><span>Staff</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="staff-list" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="staff.php">
              <i class="bi bi-circle"></i><span>Staff List</span>
            </a>
          </li>
          <li>
            <a href="staff-archived.php">
              <i class="bi bi-circle"></i><span>Archived</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="loan-plan.php">
          <i class="bi bi-file-earmark-ruled"></i>
          <span>Loan Plan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="">
          <i class="bi bi-file-earmark-text"></i>
          <span>Report</span>
        </a>
      </li>
    </ul>
  <?php } elseif ($_SESSION['role'] === 'Member') { ?>
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link" href="">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#loan-member-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-clipboard"></i><span>Loan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="loan-member-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="apply-for-loan.php">
              <i class="bi bi-circle"></i><span>Apply for Loan</span>
            </a>
          </li>
          <li>
            <a href="loan-status.php">
              <i class="bi bi-circle"></i><span>Loan Status</span>
            </a>
          </li>
          <li>
            <a href="loan-history.php">
              <i class="bi bi-circle"></i><span>Loan History</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="my-loans.php">
          <i class="bi bi-collection"></i>
          <span>My Loans</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#repayment-member-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-receipt-cutoff"></i><span>Repayment</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="repayment-member-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="repayment-schedule.php">
              <i class="bi bi-circle"></i><span>Schedule</span>
            </a>
          </li>
          <li>
            <a href="payment-history.php">
              <i class="bi bi-circle"></i><span>Payment History</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  <?php } elseif ($_SESSION['role'] === 'Staff') { ?>
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link" href="">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="">
          <i class="bi bi-clipboard"></i>
          <span>Loan Application</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#loan-staff-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-collection"></i><span>Loan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="loan-staff-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>Approved</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Loan List</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#repayment-staff-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-receipt-cutoff"></i><span>Repayment</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="repayment-staff-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>All Payment</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Overdue Payment</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Payment History</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="">
          <i class="bi bi-file-earmark-text"></i>
          <span>Report</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="">
          <i class="bi bi-people"></i>
          <span>Members</span>
        </a>
      </li>
    </ul>
  <?php } ?>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var currentPage = window.location.pathname.split('/').pop();
    var navLinks = document.querySelectorAll('.sidebar-nav a.nav-link, .sidebar-nav .nav-content a');

    navLinks.forEach(function(link) {
      if (link.getAttribute('href') === currentPage) {
        link.classList.remove('collapsed');
        link.classList.add('active'); // Highlight the current link
        var parent = link.closest('.nav-content');
        if (parent) {
          parent.classList.add('show');
          var parentLink = parent.previousElementSibling;
          if (parentLink) {
            parentLink.classList.remove('collapsed');
            parentLink.classList.add('active'); // Highlight the parent link
          }
        }
      } else {
        link.classList.add('collapsed');
      }
    });

    var collapseLinks = document.querySelectorAll('.sidebar-nav [data-bs-toggle="collapse"]');
    collapseLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        collapseLinks.forEach(function(otherLink) {
          if (otherLink !== link) {
            var target = document.querySelector(otherLink.getAttribute('data-bs-target'));
            if (target) {
              target.classList.remove('show');
              otherLink.classList.add('collapsed');
            }
          }
        });
      });
    });
  });
</script>