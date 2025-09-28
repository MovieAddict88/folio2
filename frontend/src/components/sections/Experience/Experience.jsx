import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import './Experience.css';

const Experience = () => {
  const [experienceData, setExperienceData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/experience.php')
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        // Attempt to parse media_thumbnails if it's a JSON string
        const parsedData = data.map(exp => {
          try {
            return {
              ...exp,
              media_thumbnails: JSON.parse(exp.media_thumbnails)
            };
          } catch (e) {
            return { ...exp, media_thumbnails: [] };
          }
        });
        setExperienceData(parsedData);
        setLoading(false);
      })
      .catch(error => {
        setError(error.message);
        setLoading(false);
      });
  }, []);

  if (loading) return <div className="experience-section"><h2>Experience</h2><p>Loading...</p></div>;
  if (error) return <div className="experience-section"><h2>Experience</h2><p>Error: {error}</p></div>;

  return (
    <div className="experience-section">
      <h2>Experience</h2>
      <Swiper
        modules={[Navigation, Pagination]}
        spaceBetween={50}
        slidesPerView={1}
        navigation
        pagination={{ clickable: true }}
      >
        {experienceData.map((exp, index) => (
          <SwiperSlide key={index}>
            <div className="experience-item">
              <h3>{exp.title}</h3>
              <p><strong>{exp.institution}</strong> ({exp.start_year} - {exp.end_year})</p>
              <p>{exp.description}</p>
              {exp.media_thumbnails && exp.media_thumbnails.length > 0 && (
                <div className="thumbnail-gallery">
                  {exp.media_thumbnails.map((thumb, i) => (
                    <img key={i} src={thumb} alt={`${exp.title} thumbnail ${i + 1}`} className="thumbnail" />
                  ))}
                </div>
              )}
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
};

export default Experience;