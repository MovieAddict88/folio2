import React, { useState, useEffect } from 'react';
import { Radar, RadarChart, PolarGrid, PolarAngleAxis, PolarRadiusAxis, BarChart, Bar, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer } from 'recharts';
import './Skills.css';

const Skills = () => {
  const [skills, setSkills] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/skills.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        setSkills(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  const softSkills = skills.filter(s => s.category === 'soft');
  const hardSkills = skills.filter(s => s.category === 'hard');

  if (loading) return <div className="skills-section"><h2>Skills</h2><p>Loading...</p></div>;
  if (error) return <div className="skills-section"><h2>Skills</h2><p>Error: {error}</p></div>;

  return (
    <div className="skills-section">
      <h2>Skills</h2>
      <div className="skills-container">
        <div className="skills-column">
          <h3>Soft Skills</h3>
          <ResponsiveContainer width="100%" height={400}>
            <RadarChart cx="50%" cy="50%" outerRadius="80%" data={softSkills}>
              <PolarGrid />
              <PolarAngleAxis dataKey="skill_name" />
              <PolarRadiusAxis />
              <Radar name="Level" dataKey="level" stroke="#8884d8" fill="#8884d8" fillOpacity={0.6} />
            </RadarChart>
          </ResponsiveContainer>
        </div>
        <div className="skills-column">
          <h3>Hard Skills</h3>
          <ResponsiveContainer width="100%" height={400}>
            <BarChart data={hardSkills} layout="vertical">
              <XAxis type="number" />
              <YAxis type="category" dataKey="skill_name" width={150} />
              <Tooltip />
              <Legend />
              <Bar dataKey="level" fill="#82ca9d" />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

export default Skills;