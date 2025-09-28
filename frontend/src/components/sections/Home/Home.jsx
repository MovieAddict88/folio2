import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation } from 'swiper/modules';
import QRCode from 'qrcode.react';
import 'swiper/css';
import 'swiper/css/navigation';
import './Home.css';

const Home = () => {
  const [homeData, setHomeData] = useState(null);
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let isMounted = true;
    const fetchData = async () => {
      try {
        const aboutResponse = await fetch('/api/about.php');
        const aboutData = await aboutResponse.json();
        if (isMounted) {
          if (aboutData.error) throw new Error(aboutData.error);
          setHomeData(aboutData);
        }

        const projectsResponse = await fetch('/api/projects.php?limit=3');
        const projectsData = await projectsResponse.json();
        if (isMounted) {
          if (projectsData.error) throw new Error(projectsData.error);
          setProjects(projectsData);
        }
      } catch (err) {
        if (isMounted) setError(err.message);
      } finally {
        if (isMounted) setLoading(false);
      }
    };

    fetchData();

    return () => {
      isMounted = false;
    };
  }, []);

  const generateVCard = () => {
    if (!homeData) return '';
    return `BEGIN:VCARD
VERSION:3.0
FN:${homeData.name}
EMAIL:${homeData.email}
TEL:${homeData.phone}
URL;type=linkedin:${homeData.linkedin_url}
END:VCARD`;
  };

  if (loading) return <div className="home-section">Loading...</div>;
  if (error) return <div className="home-section">Error: {error}</div>;

  return (
    <div className="home-section">
      <div className="hero-content">
        {homeData && (
          <>
            <img src={homeData.photo_url} alt={homeData.name} className="profile-pic" />
            <h1>{homeData.name}</h1>
            <p className="tagline">"{homeData.tagline}"</p>
          </>
        )}
        <div className="cta-buttons">
          <a href="/path/to/resume.pdf" className="cta-button" download>Resume</a>
          <a href="#contact" className="cta-button">Contact</a>
        </div>
      </div>
      <div className="mini-highlights-carousel">
        <h3>Highlights</h3>
        <Swiper modules={[Navigation]} spaceBetween={30} slidesPerView={1} navigation>
          {projects.map(p => (
            <SwiperSlide key={p.title}>
              <div className="highlight-item">
                <img src={p.media_url} alt={p.title} />
                <h4>{p.title}</h4>
              </div>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
      <div className="qr-code">
        <QRCode value={generateVCard()} size={128} />
        <p>Scan for contact card</p>
      </div>
    </div>
  );
};

export default Home;