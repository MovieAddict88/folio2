import React, { useState, useEffect } from 'react';
import { BarChart, Bar, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer, LineChart, Line } from 'recharts';
import './ManageAnalytics.css';

const ManageAnalytics = () => {
  const [analyticsData, setAnalyticsData] = useState({ downloads: [], visitors: [] });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchAnalytics = async () => {
      setLoading(true);
      try {
        const response = await fetch('/api/admin/analytics.php');
        const data = await response.json();
        if (data.error) throw new Error(data.error);
        setAnalyticsData(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchAnalytics();
  }, []);

  if (loading) return <p>Loading analytics...</p>;
  if (error) return <p className="error-message">Error: {error}</p>;

  return (
    <div className="manage-analytics">
      <h2>Portfolio Analytics</h2>
      <div className="charts-container">
        <div className="chart">
          <h3>Download Counts</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={analyticsData.downloads}>
              <XAxis dataKey="file_name" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Bar dataKey="download_count" fill="#8884d8" name="Downloads" />
            </BarChart>
          </ResponsiveContainer>
        </div>
        <div className="chart">
          <h3>Visitor Traffic (Placeholder)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={analyticsData.visitors}>
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Line type="monotone" dataKey="visits" stroke="#82ca9d" name="Visits" />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

export default ManageAnalytics;