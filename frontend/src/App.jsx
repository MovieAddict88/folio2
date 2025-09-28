import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/admin/ProtectedRoute';

// Layout component for the public-facing portfolio
import PortfolioLayout from './PortfolioLayout';

// Admin Components
import LoginPage from './components/admin/LoginPage';
import AdminDashboard from './components/admin/AdminDashboard';
import ManageProjects from './components/admin/ManageProjects';
import ManageTestimonials from './components/admin/ManageTestimonials';
import ManageDownloads from './components/admin/ManageDownloads';
import ManageAnalytics from './components/admin/ManageAnalytics';

import './App.css';

// A simple component for the dashboard landing page
const AdminHome = () => <h3>Welcome to your Dashboard</h3>;

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Public Portfolio Route */}
          <Route path="/*" element={<PortfolioLayout />} />

          {/* Admin Routes */}
          <Route path="/admin/login" element={<LoginPage />} />
          <Route
            path="/admin/dashboard"
            element={
              <ProtectedRoute>
                <AdminDashboard />
              </ProtectedRoute>
            }
          >
            <Route index element={<AdminHome />} />
            <Route path="projects" element={<ManageProjects />} />
            <Route path="testimonials" element={<ManageTestimonials />} />
            <Route path="downloads" element={<ManageDownloads />} />
            <Route path="analytics" element={<ManageAnalytics />} />
          </Route>
          {/* Redirect base /admin to login */}
          <Route path="/admin" element={<Navigate to="/admin/login" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;