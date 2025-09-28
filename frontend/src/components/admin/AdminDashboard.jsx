import React, { useContext } from 'react';
import { useNavigate, Link, Outlet } from 'react-router-dom';
import { AuthContext } from '../../context/AuthContext';
import './AdminDashboard.css';

const AdminDashboard = () => {
  const { logout } = useContext(AuthContext);
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/admin/login');
  };

  return (
    <div className="admin-dashboard">
      <nav className="dashboard-sidebar">
        <h3>Admin Menu</h3>
        <ul>
          <li><Link to="/admin/dashboard/projects">Manage Projects</Link></li>
          <li><Link to="/admin/dashboard/testimonials">Manage Testimonials</Link></li>
          <li><Link to="/admin/dashboard/downloads">Manage Downloads</Link></li>
          <li><Link to="/admin/dashboard/analytics">View Analytics</Link></li>
        </ul>
        <button onClick={handleLogout} className="logout-button">Logout</button>
      </nav>
      <main className="dashboard-main-content">
        <header className="dashboard-header">
          <h1>Admin Dashboard</h1>
        </header>
        <div className="dashboard-content">
          {/* Nested route content will be rendered here */}
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default AdminDashboard;