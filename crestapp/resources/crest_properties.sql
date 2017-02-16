-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2017 at 10:48 AM
-- Server version: 5.5.52-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crest_properties`
--

-- --------------------------------------------------------

--
-- Table structure for table `crest_agents`
--

CREATE TABLE IF NOT EXISTS `crest_agents` (
  `agent_id` varchar(300) CHARACTER SET utf8 NOT NULL,
  `agent_display_name` text CHARACTER SET utf8 NOT NULL,
  `team_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `staff_id` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  UNIQUE KEY `agent_id` (`agent_id`),
  KEY `team_id` (`team_id`,`staff_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crest_feeds`
--

CREATE TABLE IF NOT EXISTS `crest_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `brand_code` varchar(100) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `token` text,
  `token_expires` text,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `crest_feeds`
--

INSERT INTO `crest_feeds` (`id`, `user`, `pwd`, `brand_code`, `updated`, `token`, `token_expires`) VALUES
(1, 'WSSIR800310', 'm!yEDR5w', 'SIR', '2017-02-14 17:43:47', 'ObSSOCookie=x2in57YlX5AI7wJG7zi9v5a8I4z7jLiII1nnyVvh02o1qo3vPTNi%2Fm0%2BfcDtKfJJVNzP6Q1NUzXHdQvPnDsv%2FKH5AwPkxgHSLvOhtk%2BGvfONviFLIAy6b2SvyJG53F6Ybf8K1nw%2FokraPl%2BUJjT%2F8xvre6US1tF4NzESunOZWItPWFZqE%2Fn9VgmPf83orTHquxxf0WXdBd%2FB3CQ%2B0LMQd3WzB8TUN5QXmLO0%2BsDfYbKG8tS4fp%2FtD5z5%2FJuXr%2FgWOVIvLQ4OOdgDpAUdgpb%2Fef3cx3zi37a0nFqpW4WbBMLrlG6dx5AQAXyLz5gvaio%2FlxuyRkkZccMJ1Yua%2FhpC', '2017-02-14T18:02:00.7150819Z');

-- --------------------------------------------------------

--
-- Table structure for table `crest_feed_properties`
--

CREATE TABLE IF NOT EXISTS `crest_feed_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `Property_ID` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feed_id` (`feed_id`),
  KEY `property_id` (`Property_ID`),
  KEY `feed_id_2` (`feed_id`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_properties`
--

CREATE TABLE IF NOT EXISTS `crest_properties` (
  `Property_ID` varchar(300) NOT NULL,
  `MLS_ID` varchar(100) NOT NULL,
  `TermDays` mediumtext NOT NULL,
  `IsForeClosure` int(11) DEFAULT NULL,
  `IsShortSale` int(11) DEFAULT NULL,
  `IsForAuction` int(11) DEFAULT NULL,
  `OccupancyRate` mediumtext NOT NULL,
  `AvailableFloors` mediumtext NOT NULL,
  `LoanPayment` mediumtext NOT NULL,
  `DownPayment` mediumtext NOT NULL,
  `GrossIncome` mediumtext NOT NULL,
  `NetoperatingIncome` mediumtext NOT NULL,
  `TotalExpenses` mediumtext NOT NULL,
  `CashFlow` mediumtext NOT NULL,
  `AvailableSpace` mediumtext NOT NULL,
  `ListPrice` mediumtext,
  `Rent` text,
  `RentCurrency` text,
  `ListPriceCurrency` mediumtext,
  `ListedDate` datetime DEFAULT NULL,
  `ListingContractDate` datetime DEFAULT NULL,
  `ExpirationDate` datetime DEFAULT NULL,
  `IsCallToShow` mediumtext,
  `IsNewConstruction` mediumtext,
  `Status` mediumtext,
  `WebURL` mediumtext,
  `RFGListingID` mediumtext,
  `OfficeId` mediumtext,
  `OfficeName` mediumtext,
  `IsShownOnInternet` mediumtext,
  `IsShowAddressOnInternet` mediumtext,
  `IsHideListPrice` mediumtext,
  `IsAllowMapping` mediumtext,
  `IsPriceuponRequest` mediumtext,
  `DateAvailable` datetime DEFAULT NULL,
  `BrandName` mediumtext,
  `BrandCode` mediumtext,
  `AssociatedAgents` mediumtext,
  `PrimaryAgentName` text,
  `PrimaryAgentId` text,
  `PrimaryAgentStaffId` text,
  `PropertyDescription` mediumtext,
  `ListingRemarks` mediumtext,
  `SourceSystemID` mediumtext,
  `GeographicRegions` mediumtext,
  `ListingFeature` mediumtext,
  `ListingFees` mediumtext,
  `DevelopmentID` mediumtext,
  `LastUpdatedDate` mediumtext,
  `ClosedDate` datetime DEFAULT NULL,
  `WithdrawnOn` mediumtext,
  `LastUpdatedBy` mediumtext,
  `EstimatedCloseDate` datetime DEFAULT NULL,
  `OfficeTradeName` mediumtext,
  `PropertyStyleCode` mediumtext,
  `PropertyStyle` mediumtext,
  `PropertyLocation` mediumtext,
  `AddressLine1` mediumtext,
  `AddressLine2` mediumtext,
  `AddressLine3` mediumtext,
  `City` mediumtext,
  `County` mediumtext,
  `StateCode` mediumtext,
  `StateName` mediumtext,
  `PostalCode` mediumtext,
  `CountryCode` mediumtext,
  `CountryName` mediumtext,
  `AddressType` mediumtext,
  `PropertyLocationDescription` mediumtext,
  `BuildingArea` mediumtext,
  `Area` mediumtext,
  `AreaUnit` mediumtext,
  `YearBuilt` mediumtext,
  `YearRenovated` mediumtext,
  `LotSize` mediumtext,
  `LotArea` mediumtext,
  `LotAreaUnit` mediumtext,
  `LotDimension` mediumtext,
  `PropertyUse` mediumtext,
  `NoOfParkingPlaces` mediumtext,
  `FullBath` mediumtext,
  `HalfBath` mediumtext,
  `LastSoldOn` mediumtext,
  `PropertyFeatures` mediumtext,
  `NumberOfLevels` mediumtext,
  `Floors` mediumtext,
  `ZoomLevel` mediumtext,
  `SourcePropertyType` mediumtext,
  `VersionNumber` mediumtext,
  `Zoning` mediumtext,
  `TaxRollNo` mediumtext,
  `NoOfBedrooms` mediumtext,
  `ThreeQuarterBath` mediumtext,
  `QuarterBath` mediumtext,
  `Rooms` mediumtext,
  `Schools` mediumtext,
  `TotalRooms` mediumtext,
  `BuildingClass` mediumtext,
  `BuildingClassCode` mediumtext,
  `NoOfDocks` mediumtext,
  `CapRatePercent` mediumtext,
  `MaxContiguousArea` mediumtext,
  `MinDivisibleArea` mediumtext,
  `ParkingRatio` mediumtext,
  `CommonAreaFactor` mediumtext,
  `TaxIDNumber` mediumtext,
  `ScheduleIncome` mediumtext,
  `TotalUnits` mediumtext,
  `AverageOccupancyRate` mediumtext,
  `NumberofBallrooms` mediumtext,
  `NumberofConferenceRooms` mediumtext,
  `BayDepth` mediumtext,
  `Clearance` mediumtext,
  `DockHeight` mediumtext,
  `IsGroundLevel` mediumtext,
  `Power` varchar(300) DEFAULT NULL,
  `TurningRadius` varchar(300) DEFAULT NULL,
  `IsCrossDocks` varchar(300) DEFAULT NULL,
  `HasRailAccess` varchar(300) DEFAULT NULL,
  `IsSubLease` varchar(300) DEFAULT NULL,
  `IsSprinkler` varchar(300) DEFAULT NULL,
  `AgriculturalPropertyNumber` int(11) NOT NULL,
  `AverageFloorSize` varchar(300) DEFAULT NULL,
  `ColumnSpacing` varchar(300) DEFAULT NULL,
  `CeilingHeight` varchar(300) DEFAULT NULL,
  `AnchorStores` varchar(300) DEFAULT NULL,
  `SuiteApartmentName` varchar(300) DEFAULT NULL,
  `SubUnits` varchar(300) DEFAULT NULL,
  `PropertySubType` varchar(300) DEFAULT NULL,
  `PropertyType` varchar(300) DEFAULT NULL,
  `Latitude` varchar(300) DEFAULT NULL,
  `Longitude` varchar(300) DEFAULT NULL,
  `TotalAcres` varchar(300) DEFAULT NULL,
  `defaultPropertyName` varchar(300) DEFAULT NULL,
  `PricePerArea` varchar(300) DEFAULT NULL,
  `FullyLeasedIncome` varchar(300) DEFAULT NULL,
  `TaxYear` varchar(300) DEFAULT NULL,
  `AnnualTax` varchar(300) DEFAULT NULL,
  `AdditionalMLS` varchar(300) DEFAULT NULL,
  `AlternateListPrice` varchar(300) DEFAULT NULL,
  `AlternateListPriceCurrency` varchar(20) DEFAULT NULL,
  `wovaxUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`Property_ID`),
  KEY `MLS_ID` (`MLS_ID`),
  KEY `MLS_ID_2` (`MLS_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_agents`
--

CREATE TABLE IF NOT EXISTS `crest_property_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `agent_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `is_primary` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Property_ID` (`Property_ID`,`MLS_ID`,`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_features`
--

CREATE TABLE IF NOT EXISTS `crest_property_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) DEFAULT NULL,
  `Property_ID` varchar(100) DEFAULT NULL,
  `FeatureName` mediumtext,
  `FeatureGroup` mediumtext,
  `FeatureCode` mediumtext,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_georegions`
--

CREATE TABLE IF NOT EXISTS `crest_property_georegions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `type_code` text CHARACTER SET utf8,
  `area_type` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`),
  KEY `MLS_ID_2` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_images`
--

CREATE TABLE IF NOT EXISTS `crest_property_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(200) DEFAULT NULL,
  `MLS_ID` varchar(100) DEFAULT NULL,
  `Property_ID` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_remarks`
--

CREATE TABLE IF NOT EXISTS `crest_property_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MLS_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `Property_ID` varchar(100) CHARACTER SET utf8 NOT NULL,
  `remark_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `MLS_ID` (`MLS_ID`,`Property_ID`,`remark_name`),
  KEY `MLS_ID_2` (`MLS_ID`,`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `crest_property_schools`
--

CREATE TABLE IF NOT EXISTS `crest_property_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Property_ID` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `MLS_ID` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `type` text CHARACTER SET utf8,
  `name` text CHARACTER SET utf16,
  PRIMARY KEY (`id`),
  KEY `Property_ID` (`Property_ID`,`MLS_ID`),
  KEY `Property_ID_2` (`Property_ID`,`MLS_ID`),
  KEY `Property_ID_3` (`Property_ID`,`MLS_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `update_queue`
--

CREATE TABLE IF NOT EXISTS `update_queue` (
  `Property_ID` varchar(100) NOT NULL,
  `status` varchar(10) NOT NULL,
  `type` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`Property_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
