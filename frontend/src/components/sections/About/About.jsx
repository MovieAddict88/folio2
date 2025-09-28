import React, { useState, useEffect } from 'react';
import './About.css';

const About = () => {
  const [aboutData, setAboutData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/about.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        setAboutData(data);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  const getEmbedUrl = (url) => {
    if (!url) return null;
    try {
      const urlObj = new URL(url);
      if (urlObj.hostname.includes('youtube.com')) {
        const videoId = urlObj.searchParams.get('v');
        return `https://www.youtube.com/embed/${videoId}`;
      }
      return url;
    } catch (e) {
      return null;
    }
  };

  if (loading) return <div className="about-section"><h2>About Me</h2><p>Loading...</p></div>;
  if (error) return <div className="about-section"><h2>About Me</h2><p>Error: {error}</p></div>;

  return (
    <div className="about-section">
      <h2>About Me</h2>
      {aboutData && (
        <>
          <p>{aboutData.bio}</p>
          <h3>My Philosophy</h3>
          <p>{aboutData.philosophy}</p>
          {aboutData.video_url && (
            <div className="video-container">
              <iframe
                src={getEmbedUrl(aboutData.video_url)}
                title="Introductory Video"
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              ></iframe>
            </div>
          )}
        </>
      )}
    </div>
  );
};

export default About;